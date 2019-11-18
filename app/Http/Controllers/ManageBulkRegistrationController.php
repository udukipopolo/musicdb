<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\Request;
use SplFileObject;

class ManageBulkRegistrationController extends Controller
{
    public function index()
    {
        $params = [];

        return view('manage.bulk.regist.index', $params);
    }

    public function csv(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'csv_file' => 'required|file|mimetypes:text/plain|mimes:csv,txt',
            ],
            [
                'csv_file.required'  => 'ファイルを選択してください。',
                'csv_file.file'      => 'ファイルアップロードに失敗しました。',
                'csv_file.mimetypes' => 'ファイル形式が不正です。',
                'csv_file.mimes'     => 'ファイル拡張子が異なります。',
            ],
            []
        );

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            \DB::transaction(function () use($request) {
                \setlocale(LC_ALL, 'ja_JP.UTF-8');

                $uploaded_file = $request->file('csv_file');

                $file_path = $request->file('csv_file')->path($uploaded_file);

                $file = new SplFileObject($file_path);
                $file->setFlags(SplFileObject::READ_CSV);

                $row_count = 1;
                foreach ($file as $row_data) {
                    if ($row_count > 1) {
                        $row = collect($row_data);
                        // $row->transform(function($item, $key) {
                        //     return mb_convert_encoding($item, 'UTF-8', 'SJIS');
                        // });

                        // アルバムアーティスト
                        $album_artist = Artist::where('name', $row->get(1))->first();
                        if (!$album_artist) {
                            $album_artist = Artist::create([
                                'name' => $row->get(1),
                                'belonging' => '',
                            ]);
                        }

                        // アルバム
                        $album = Album::where('title', $row->get(0))
                            ->where('artist_id', $album_artist->id)
                            ->first();
                        if (!$album) {
                            $album = Album::create([
                                'title' => $row->get(0),
                                'artist_id' => $album_artist->id,
                                'artist_name' => $row->get(2),
                                'description' => '',
                            ]);
                        }

                        // 楽曲
                        $music = $album->musics()->where('track_no', $row->get(3))->first();
                        if (!$music) {
                            $music = $album->musics()->create([
                                'title' => $row->get(4),
                                'track_no' => $row->get(3),
                            ]);
                        }

                        // パートアーティスト
                        $part_artist = Artist::where('name', $row->get(6))->first();
                        if (!$part_artist) {
                            $part_artist = Artist::create([
                                'name' => $row->get(6),
                                'belonging' => '',
                            ]);
                        }

                        // パート
                        $part = Part::where('artist_id', $part_artist->id)
                            ->where('artist_name', $row->get(7))
                            ->where('part_name', $row->get(5))
                            ->first();
                        if (!$part) {
                            $music->parts()->create([
                                'artist_id' => $part_artist->id,
                                'artist_name' => $row->get(7),
                                'part_name' => $row->get(5),
                            ]);
                        }
                    }

                    $row_count++;
                }
            });
        } catch(\Exception $e) {
            $validator->after(function($validator) {
                $validator->errors()->add('csv_file', 'データの登録に失敗しました。');
            });
        }

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        return redirect()->route('manage.bulk.regist.index')->with('message', 'CSV登録が完了しました。');
    }
}
