<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Music;
use Illuminate\Http\Request;

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
                'title' => 'required|max:255',
                'artist_id' => '',
                'artist_name' => 'required|max:255',
                'musics' => 'array',
                'musics.*' => 'max:255',
            ],
            [],
            [
                'title' => 'アルバムタイトル',
                'artist_id' => '',
                'artist_name' => 'アーティスト名',
                'musics.*' => '楽曲名',
            ]
        );

        $album = null;

        \DB::transaction(function () use($request, &$album) {
            $artist = Artist::find($request->input('artist_id'));
            if (!$artist) {
                $artist = Artist::create([
                    'name' => $request->artist_name,
                    'belonging' => '',
                ]);
            }

            $album = Album::create([
                'title' => $request->title,
                'artist_id' => $artist->id,
                'artist_name' => $request->artist_name,
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
                'title' => 'required|max:255',
                'artist_id' => '',
                'artist_name' => 'required|max:255',
                'musics' => 'array',
                'musics.*' => 'max:255',
            ],
            [],
            [
                'title' => 'アルバムタイトル',
                'artist_id' => '',
                'artist_name' => 'アーティスト名',
                'musics.*' => '楽曲名',
            ]
        );

        \DB::transaction(function () use($request, &$album) {
            $artist = Artist::find($request->input('artist_id'));
            if (!$artist) {
                $artist = Artist::create([
                    'name' => $request->artist_name,
                    'belonging' => '',
                ]);
            }

            $album->title = $request->title;
            $album->artist_id = $artist->id;
            $album->artist_name = $request->artist_name;
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
        //
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

    }
}
