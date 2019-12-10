<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Music;
use Illuminate\Http\Request;

class SearchMusicController extends Controller
{
    public function index(Request $request)
    {
        $params = [];

        $input = $request->input();
        $params['input'] = $input;

        if (count($request->query()) > 0) {
            $musics = Music::query();

            if ($request->filled('album_title')) {
                $musics->whereHas('album', function($query) use($request){
                    $query->where('title', 'LIKE', '%'.$request->album_title.'%');
                });
            }

            if ($request->filled('music_title')) {
                $musics->where('title', 'LIKE', '%'.$request->music_title.'%');
            }

            if ($request->filled('artist_name')) {
                $artists = Artist::where('name', 'LIKE', '%'.$request->artist_name.'%')
                    ->get()
                    ->pluck('id');
                $musics->whereHas('parts', function($parts) use($request, $artists) {
                    $parts->where('artist_name', 'LIKE', '%'.$request->artist_name.'%')
                        ->orWhereIn('artist_id', $artists);
                })
                ->orWhereHas('album', function($album) use($request, $artists) {
                    $album->where('artist_name', 'LIKE', '%'.$request->artist_name.'%')
                        ->orWhereIn('artist_id', $artists);
                });
            }

            // 詳細検索
            if ($request->filled('album_artist')) {
                $musics->whereHas('album', function($query) use($request) {
                    $album_artists = Artist::where('name', 'LIKE', '%'.$request->album_artist.'%')
                    ->get()
                    ->pluck('id');
                    $query->where('artist_name', 'LIKE', '%'.$request->album_artist.'%')
                        ->orWhereIn('artist_id', $album_artists);
                });
            }

            if ($request->filled('music_artist') || $request->filled('music_part')) {
                $musics->whereHas('parts', function($parts) use($request) {
                    if ($request->filled('music_artist')) {
                        $music_artists = Artist::where('name', 'LIKE', '%'.$request->music_artist.'%')
                        ->get()
                        ->pluck('id');
                        $parts->where(function($group) use($request, $music_artists) {
                            $group->where('artist_name', 'LIKE', '%'.$request->music_artist.'%')
                            ->orWhereIn('artist_id', $music_artists);
                        });
                    }
                    if ($request->filled('music_part')) {
                        $parts->where('part_name', $request->music_part);
                    }
                });
            }

            $params['musics'] = $musics->paginate(20);
        }

        return view('search.music.index', $params);
    }

    public function show(Music $music)
    {
        $params = [
            'music' => $music,
        ];

        return view('search.music.show', $params);
    }

    public function album(Album $album)
    {
        $params = [
            'album' => $album,
        ];

        return view('search.album.show', $params);
    }
}
