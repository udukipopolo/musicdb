<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Part;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use NoRewindIterator;
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
            ]
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
                            $album_artist->locale_name()->updateOrCreate(
                                [
                                    'column' => 'artist_name',
                                    'locale' => 'ja',
                                ],
                                [
                                    'name' => $row->get(1),
                                ]
                            );
                            $album_artist->locale_text()->updateOrCreate(
                                [
                                    'column' => 'belonging',
                                    'locale' => 'ja',
                                ],
                                [
                                    'text' => '',
                                ]
                            );

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
                            $album->locale_name()->updateOrCreate(
                                [
                                    'column' => 'title',
                                    'locale' => 'ja',
                                ],
                                [
                                    'name' => $row->get(0),
                                ]
                            );
                            $album->locale_name()->updateOrCreate(
                                [
                                    'column' => 'artist_name',
                                    'locale' => 'ja',
                                ],
                                [
                                    'name' => $row->get(2),
                                ]
                            );
                            $album->locale_text()->updateOrCreate(
                                [
                                    'column' => 'description',
                                    'locale' => 'ja',
                                ],
                                [
                                    'text' => '',
                                ]
                            );

                        }

                        // 楽曲
                        $music = $album->musics()->where('track_no', $row->get(3))->first();
                        if (!$music) {
                            $music = $album->musics()->create([
                                'title' => $row->get(4),
                                'track_no' => $row->get(3),
                            ]);
                            $music->locale_name()->updateOrCreate(
                                [
                                    'column' => 'title',
                                    'locale' => 'ja',
                                ],
                                [
                                    'name' => $row->get(4),
                                ]
                            );

                        }

                        // パートアーティスト
                        $part_artist = Artist::where('name', $row->get(6))->first();
                        if (!$part_artist) {
                            $part_artist = Artist::create([
                                'name' => $row->get(6),
                                'belonging' => '',
                            ]);
                            $part_artist->locale_name()->updateOrCreate(
                                [
                                    'column' => 'artist_name',
                                    'locale' => 'ja',
                                ],
                                [
                                    'name' => $row->get(6),
                                ]
                            );
                            $part_artist->locale_text()->updateOrCreate(
                                [
                                    'column' => 'belonging',
                                    'locale' => 'ja',
                                ],
                                [
                                    'text' => '',
                                ]
                            );

                        }

                        // パート
                        $part = $music->parts()
                            ->where('artist_id', $part_artist->id)
                            ->where('artist_name', $row->get(7))
                            ->where('name', $row->get(5))
                            ->first();
                        if (!$part) {
                            $music->parts()->create([
                                'artist_id' => $part_artist->id,
                                'artist_name' => $row->get(7),
                                'name' => $row->get(5),
                            ]);
                            $part->locale_name()->updateOrCreate(
                                [
                                    'column' => 'artist_name',
                                    'locale' => 'ja',
                                ],
                                [
                                    'name' => $row->get(7),
                                ]
                            );
                            $part->locale_name()->updateOrCreate(
                                [
                                    'column' => 'name',
                                    'locale' => 'ja',
                                ],
                                [
                                    'name' => $row->get(5),
                                ]
                            );

                        }
                    }

                    $row_count++;
                }
            });
        } catch(\Exception $e) {
            \Log::debug($e->getMessage());
            $validator->after(function($validator) {
                $validator->errors()->add('csv_file', __('messages.csv_file.failed'));
            });
        }

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        return redirect()->route('manage.bulk.regist.index')->with('message', __('messages.csv_file.complete'));
    }

    public function googlespreadsheet(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'gs_url' => [
                    'required',
                    'active_url',
                    'regex:/^https:\/\/docs\.google\.com\/spreadsheets\/.+$/u',
                ],
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            \DB::transaction(function () use($request) {
                $matches = null;
                preg_match('/^https:\/\/docs\.google\.com\/spreadsheets\/d\/(.+)\/edit.+$/u', $request->url, $matches);

                if (!isset($matches[1])) {
                    throw new Exception();
                }

                \setlocale(LC_ALL, 'ja_JP.UTF-8');

                $url = "https://docs.google.com/spreadsheets/d/{$matches[1]}/export?format=csv";

                $splObj = new SplFileObject($url);
                $splObj->setFlags(SplFileObject::READ_CSV);
                $file = new NoRewindIterator($splObj);

                $row_count = 1;
                foreach ($file as $row_data) {
                    if ($row_count > 1) {
                        $row = collect($row_data);

                        // アルバムアーティスト
                        $album_artist = Artist::where('name', $row->get(1))->first();
                        if (!$album_artist) {
                            $album_artist = Artist::create([
                                'name' => $row->get(1),
                                'belonging' => '',
                            ]);
                            $album_artist->locale_name()->updateOrCreate(
                                [
                                    'column' => 'artist_name',
                                    'locale' => 'ja',
                                ],
                                [
                                    'name' => $row->get(1),
                                ]
                            );
                            $album_artist->locale_text()->updateOrCreate(
                                [
                                    'column' => 'belonging',
                                    'locale' => 'ja',
                                ],
                                [
                                    'text' => '',
                                ]
                            );

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
                            $album->locale_name()->updateOrCreate(
                                [
                                    'column' => 'title',
                                    'locale' => 'ja',
                                ],
                                [
                                    'name' => $row->get(0),
                                ]
                            );
                            $album->locale_name()->updateOrCreate(
                                [
                                    'column' => 'artist_name',
                                    'locale' => 'ja',
                                ],
                                [
                                    'name' => $row->get(2),
                                ]
                            );
                            $album->locale_text()->updateOrCreate(
                                [
                                    'column' => 'description',
                                    'locale' => 'ja',
                                ],
                                [
                                    'text' => '',
                                ]
                            );

                        }

                        // 楽曲
                        $music = $album->musics()->where('track_no', $row->get(3))->first();
                        if (!$music) {
                            $music = $album->musics()->create([
                                'title' => $row->get(4),
                                'track_no' => $row->get(3),
                            ]);
                            $music->locale_name()->updateOrCreate(
                                [
                                    'column' => 'title',
                                    'locale' => 'ja',
                                ],
                                [
                                    'name' => $row->get(4),
                                ]
                            );

                        }

                        // パートアーティスト
                        $part_artist = Artist::where('name', $row->get(6))->first();
                        if (!$part_artist) {
                            $part_artist = Artist::create([
                                'name' => $row->get(6),
                                'belonging' => '',
                            ]);
                            $part_artist->locale_name()->updateOrCreate(
                                [
                                    'column' => 'artist_name',
                                    'locale' => 'ja',
                                ],
                                [
                                    'name' => $row->get(6),
                                ]
                            );
                            $part_artist->locale_text()->updateOrCreate(
                                [
                                    'column' => 'belonging',
                                    'locale' => 'ja',
                                ],
                                [
                                    'text' => '',
                                ]
                            );

                        }

                        // パート
                        $part = $music->parts()
                            ->where('artist_id', $part_artist->id)
                            ->where('artist_name', $row->get(7))
                            ->where('part_name', $row->get(5))
                            ->first();
                        if (!$part) {
                            $part = $music->parts()->create([
                                'artist_id' => $part_artist->id,
                                'artist_name' => $row->get(7),
                                'part_name' => $row->get(5),
                            ]);
                            $part->part_artist_name()->create([
                                'column' => 'artist_name',
                                'locale' => 'ja',
                                'name' => $row->get(7),
                            ]);
                            $part->part_name()->create([
                                'column' => 'name',
                                'locale' => 'ja',
                                'name' => $row->get(5),
                            ]);

                        }
                    }

                    $row_count++;
                }
            });
        } catch(\Exception $e) {
            $validator->after(function($validator) {
                $validator->errors()->add('csv_file', __('messages.csv_file.failed'));
            });
        }

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        return redirect()->route('manage.bulk.regist.index')->with('message', __('messages.csv_file.complete'));
    }
}
