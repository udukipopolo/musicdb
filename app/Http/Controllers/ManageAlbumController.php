<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Music;
use App\Services\PhgService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManageAlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = [];

        if (count($request->query()) > 0) {
            $params['input'] = $request->input();

            $albums = Album::query();
            if ($request->filled('title')) {
                $albums->where('title', 'LIKE', '%'.$request->title.'%');
            }
            $albums->orderBy('title', 'ASC');
            $params['albums'] = $albums->paginate(50);
        }

        return view('manage.album.index', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params = [];

        $artists = Artist::orderBy('name', 'ASC')->get()->pluck('name', 'id');
        $params['artists'] = $artists;

        return view('manage.album.create', $params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => [
                    'required',
                    'max:255',
                    Rule::unique('albums')->where(function($query) use($request) {
                        return $query->where('artist_name', $request->artist_name);
                    }),
                ],
                'artist_id' => 'required|max:255',
                'artist_name' => 'max:255',
                'musics' => 'array',
                'musics.*' => 'max:255',
                'description' => '',
            ],
            [],
            [
                'title' => 'アルバムタイトル',
                'artist_id' => 'アーティスト名',
                'artist_name' => '別名義',
                'musics.*' => '楽曲名',
                'description' => '詳細・アルバムに携わった人等',
            ]
        );

        $album = null;

        \DB::transaction(function () use($request, &$album) {
            $artist = Artist::where('name', $request->input('artist_id'))->first();
            if (!$artist) {
                $artist = Artist::create([
                    'name' => $request->artist_id,
                    'belonging' => '',
                ]);
            }

            if ($request->filled('artist_name')) {
                $artist_name = $request->artist_name;
            } else {
                $artist_name = $artist->name;
            }

            $album = Album::create([
                'title' => $request->title,
                'artist_id' => $artist->id,
                'artist_name' => $artist_name,
                'description' => ($request->filled('description')) ? $request->description : '',
            ]);

            foreach ($request->musics AS $no=>$title) {
                if (empty($title)) {
                    continue;
                }
                $album->musics()->create([
                    'title' => $title,
                    'track_no' => $no,
                ]);
            }
        });

        return redirect()->route('manage.album.show', $album)->with('message', 'アルバムを登録しました。');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        $params = [
            'album' => $album,
        ];

        return view('manage.album.show', $params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        $params = [
            'album' => $album,
            'artists' => Artist::orderBy('name', 'ASC')->get()->pluck('name', 'id'),
        ];

        return view('manage.album.edit', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        $request->validate(
            [
                'title' => [
                    'required',
                    'max:255',
                    Rule::unique('albums')->where(function($query) use($request) {
                        return $query->where('artist_name', $request->artist_name);
                    })->ignore($album->id),
                ],
                'artist_id' => 'required|max:255',
                'artist_name' => 'max:255',
                'musics' => 'array',
                'musics.*' => 'max:255',
                'description' => '',
            ],
            [],
            [
                'title' => 'アルバムタイトル',
                'artist_id' => 'アーティスト名',
                'artist_name' => '別名義',
                'musics.*' => '楽曲名',
                'description' => '詳細・アルバムに携わった人等',
            ]
        );

        \DB::transaction(function () use($request, &$album) {
            $artist = Artist::where('name', $request->input('artist_id'))->first();
            if (!$artist) {
                $artist = Artist::create([
                    'name' => $request->artist_id,
                    'belonging' => '',
                ]);
            }

            if ($request->filled('artist_name')) {
                $artist_name = $request->artist_name;
            } else {
                $artist_name = $artist->name;
            }

            $album->title = $request->title;
            $album->artist_id = $artist->id;
            $album->artist_name = $artist_name;
            $album->description = ($request->filled('description')) ? $request->description : '';
            $album->save();

            foreach ($request->musics AS $no=>$title) {
                $music = $album->musics()->where('track_no', $no)->first();
                if (empty($title)) {
                    if ($music) {
                        $music->parts()->delete();
                        $music->delete();
                    }
                } else {
                    if ($music) {
                        $music->title = $title;
                        $music->save();
                    } else {
                        $album->musics()->create([
                            'title' => $title,
                            'track_no' => $no,
                        ]);
                    }
                }
            }

        });

        return redirect()->route('manage.album.show', $album)->with('message', 'アルバムを更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        foreach($album->musics as $music) {
            $music->parts()->delete();
            $music->delete();
        }
        $album->delete();

        return redirect()->route('manage.album.index')->with('message', 'アルバム情報を削除しました。');
    }

    public function editMusic(Album $album, Music $music)
    {
        if ($album->id != $music->album_id) {
            abort(404);
        }

        $params = [
            'album' => $album,
            'music' => $music,
            'artists' => Artist::orderBy('name', 'ASC')->get()->pluck('name', 'id'),
        ];

        return view('manage.album.music.edit', $params);
    }

    public function updateMusic(Request $request, Album $album, Music $music)
    {
        if ($album->id != $music->album_id) {
            abort(404);
        }

        $validator = \Validator::make(
            $request->all(),
            [
                'edit_artist_id' => 'max:255',
                'edit_artist_name.*' => 'max:255',
                'edit_part_name.*' => 'required_with:edit_artist_name.*|max:255',
                'add_artist_id' => 'max:255',
                'add_artist_name.*' => 'max:255',
                'add_part_name.*' => 'required_with:add_artist_name.*|max:255',
            ],
            [],
            [
                'edit_artist_id.*' => 'アーティスト名',
                'edit_artist_name.*' => '別名義',
                'edit_part_name.*' => 'パート名',
                'add_artist_id.*' => 'アーティスト名',
                'add_artist_name.*' => '別名義',
                'add_part_name.*' => 'パート名',
            ]
        );

        /*
        $validator->after(function($validator) use($request) {
            if ($request->filled('edit_artist_id')) {
                foreach($request->edit_artist_id as $part_id=>$artist_id) {
                    if (empty($artist_id) && $request->filled('edit_artist_name.'.$part_id)) {
                        if (Artist::where('name', $request->input('edit_artist_name.'.$part_id))->count() > 0) {
                            $validator->errors()->add('edit_artist_name.'.$part_id, '同名のアーティストが登録されています。');
                        }
                    }
                }
            }
            if ($request->filled('add_artist_id')) {
                foreach($request->add_artist_id as $no=>$artist_id) {
                    if (empty($artist_id) && $request->filled('add_artist_name.'.$no)) {
                        if (Artist::where('name', $request->input('add_artist_name.'.$no))->count() > 0) {
                            $validator->errors()->add('add_artist_name.'.$no, '同名のアーティストが登録されています。');
                        }
                    }
                }
            }
        });
        */

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        \DB::transaction(function () use($request, $album, $music) {
            // 更新
            foreach($music->parts as $part) {
                if ($request->filled('edit_artist_id.'.$part->id)) {
                    $artist = Artist::firstOrCreate([
                        'name' => $request->input('edit_artist_id.'.$part->id),
                    ]);

                    if ($request->filled('edit_artist_name.'.$part->id)) {
                        $artist_name = $request->input('edit_artist_name.'.$part->id);
                    } else {
                        $artist_name = $artist->name;
                    }

                    $part->artist_id = $artist->id;
                    $part->artist_name = $artist_name;
                    $part->part_name = $request->input('edit_part_name.'.$part->id);
                    $part->save();
                } else {
                    $part->delete();
                }
            }

            // 新規登録
            if ($request->filled('add_artist_name')) {
                foreach($request->add_artist_name as $no=>$artist_name) {
                    if (!$request->filled('add_artist_id.'.$no)) {
                        continue;
                    }

                    $artist = Artist::firstOrCreate([
                        'name' => $request->input('add_artist_id.'.$no),
                    ]);

                    if ($request->filled('add_artist_name.'.$no)) {
                        $artist_name = $request->input('add_artist_name.'.$no);
                    } else {
                        $artist_name = $artist->name;
                    }

                    $music->parts()->create([
                        'artist_id' => $artist->id,
                        'artist_name' => $artist_name,
                        'part_name' => $request->input('add_part_name.'.$no)
                    ]);
                }
            }

        });

        return redirect()->route('manage.album.show', $album->id)->with('message', '更新しました。');
    }

    public function createFromPhg(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'phg_url' => [
                    'required',
                    'active_url',
                ]
            ],
            [],
            [
                'phg_url' => 'Apple Music URL',
            ]
        );

        $validator->after(function($validator) use($request) {
            if (strpos($request->phg_url, 'https://music.apple.com/') !== 0) {
                $validator->errors()->add('phg_url', 'Apple MusicのURLを指定してください。');
            }
        });

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $phg = new PhgService();
        $phg_album_id = $phg->getId($request->phg_url);

        $query = [
            'id' => $phg_album_id,
            'entity' => 'song'
        ];
        $album_data = $phg->getLookup($query);

        $params = [];
        $album = [];
        $musics = [];
        if (isset($album_data['resultCount']) && $album_data['resultCount'] > 0) {
            foreach ($album_data['results'] as $music_data) {
                switch($music_data['wrapperType']) {
                    case 'collection':
                        // アルバム情報
                        $album = [
                            'title' => $music_data['collectionName'],
                            'artist_id' => $music_data['artistName'],
                        ];
                    break;
                    case 'track':
                        // 楽曲情報
                        $musics[] = [
                            'track_no' => $music_data['trackNumber'],
                            'title' => $music_data['trackName'],
                        ];
                    break;
                    default:
                }
            }
        }
        $params['album'] = $album;
        $params['musics'] = collect($musics);

        return view('manage.album.create_phg', $params);
    }
}
