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

    public function create()
    {
        $params = [];

        return view('manage.bulk.regist.create', $params);
    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'datas' => 'required|array',
                'datas.*.1' => 'required_with:datas.*.0',
                'datas.*.2' => 'required_with:datas.*.0',
                'datas.*.3' => 'required_with:datas.*.0',
                'datas.*.4' => 'required_with:datas.*.0',
                'datas.*.5' => 'required_with:datas.*.0',
                'datas.*.6' => 'required_with:datas.*.0',
                'datas.*.7' => 'required_with:datas.*.0',
            ]
        );

        if ($validator->fails()) {
            return collect([
                'status' => 'error',
                'error_message' => __('messages.bulk_regist.failed'),
                'errors' => $validator->errors()->toArray(),
            ])->toJson();
        }


        $result = [];
        \DB::transaction(function () use($request, &$result) {
            foreach ($request->datas as $row_no=>$row_data) {
                $result[$row_no] = $this->processRow($row_data);
            }
        });

        return collect([
            'status' => 'success',
            'result' => $result,
        ])->toJson();
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

        $errors = [];
        try {
            \DB::transaction(function () use($request, &$errors) {
                \setlocale(LC_ALL, 'ja_JP.UTF-8');

                $uploaded_file = $request->file('csv_file');

                $file_path = $request->file('csv_file')->path($uploaded_file);

                $file = new SplFileObject($file_path);
                $file->setFlags(SplFileObject::READ_CSV);

                $errors = $this->registCsv($file);
            });
        } catch(\Exception $e) {
            \Log::debug($e->getMessage());
            $validator->after(function($validator) {
                $validator->errors()->add('csv_file', __('messages.csv_file.failed'));
            });
        }

        $validator->after(function($validator) use($errors) {
            if (count($errors) > 0) {
                $message = '';
                foreach ($errors as $row_no=>$error) {
                    $message .= $row_no.':'.$error.'<br>';
                }
                $validator->errors()->add('row_error', $message);
            }
        });

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

        $errors = [];
        try {
            \DB::transaction(function () use($request, &$errors) {
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

                $errors = $this->registCsv($file);
            });
        } catch(\Exception $e) {
            $validator->after(function($validator) {
                $validator->errors()->add('csv_file', __('messages.csv_file.failed'));
            });
        }

        $validator->after(function($validator) use($errors) {
            if (count($errors) > 0) {
                foreach ($errors as $row_no=>$error) {
                    $validator->errors()->add('row_error', $row_no.':'.$error);
                }
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        return redirect()->route('manage.bulk.regist.index')->with('message', __('messages.csv_file.complete'));
    }

    private function registCsv($file)
    {
        $row_count = 1;
        $errors = [];
        foreach ($file as $row_data) {
            if ($row_count > 1) {
                $result = $this->processRow($row_data);
                switch($result) {
                    case 2:
                        $errors[$row_count] = __('messages.csv_file.row.empty');
                    break;
                    case 3:
                        $errors[$row_count] = __('messages.csv_file.row.dup');
                    break;
                    default:
                }
            }

            $row_count++;
        }

        return $errors;
    }

    private function processRow($row_data)
    {
        $row = collect($row_data);
        // $row->transform(function($item, $key) {
        //     return mb_convert_encoding($item, 'UTF-8', 'SJIS');
        // });
        if (
            empty($row->get(0)) &&
            empty($row->get(1)) &&
            empty($row->get(2)) &&
            empty($row->get(3)) &&
            empty($row->get(4)) &&
            empty($row->get(5)) &&
            empty($row->get(6)) &&
            empty($row->get(7))
        ) {
            return 0;
        }

        if (
            empty($row->get(0)) ||
            empty($row->get(1)) ||
            empty($row->get(2)) ||
            empty($row->get(3)) ||
            empty($row->get(4)) ||
            empty($row->get(5)) ||
            empty($row->get(6)) ||
            empty($row->get(7))
        ) {
            return 2;
        }

        // アルバムアーティスト
        $album_artist = Artist::where('name', $row->get(1))->first();
        if (!$album_artist) {
            $album_artist = Artist::create([
                'name' => $row->get(1),
                'belonging' => '',
            ]);
            $album_artist->locale_name()->updateOrCreate(
                [
                    'column' => 'name',
                    'locale' => 'ja',
                ],
                [
                    'artist_id' => $album_artist->id,
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
                    'artist_id' => $album->artist_id,
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
                    'column' => 'name',
                    'locale' => 'ja',
                ],
                [
                    'artist_id' => $part_artist->id,
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
            $part = $music->parts()->create([
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
                    'artist_id' => $part->artist_id,
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

        } else {
            return 3;
        }

        return 1;
    }
}
