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
                    if (mb_strlen($request->album_title) > 2) {
                        $query->whereRaw("MATCH(title) AGAINST( ? )", [$request->album_title]);
                    } else {
                        $query->where('title', 'LIKE', '%'.$request->album_title.'%');
                    }
                });
            }

            if ($request->filled('music_title')) {
                $musics->where(function($query) use($request) {
                    if (mb_strlen($request->music_title) > 2) {
                        $query->whereRaw("MATCH(title) AGAINST( ? )", [$request->music_title]);
                    } else {
                        $query->where('title', 'LIKE', '%'.$request->music_title.'%');
                    }
                });

            }

            if ($request->filled('artist_name')) {
                $artists = Artist::query();
                if (mb_strlen($request->artist_name) > 2) {
                    $artists->whereRaw("MATCH(name) AGAINST( ? )", [$request->artist_name]);
                } else {
                    $artists->where('name', 'LIKE', '%'.$request->artist_name.'%');
                }
                $artists = $artists->get()
                    ->pluck('id');
                $musics->whereHas('parts', function($parts) use($request, $artists) {
                    if (mb_strlen($request->artist_name) > 2) {
                        $parts->whereRaw("MATCH(artist_name) AGAINST( ? )", [$request->artist_name]);
                    } else {
                        $parts->where('artist_name', 'LIKE', '%'.$request->artist_name.'%');
                    }
                    $parts->orWhereIn('artist_id', $artists);
                })
                ->orWhereHas('album', function($album) use($request, $artists) {
                    if (mb_strlen($request->artist_name) > 2) {
                        $album->whereRaw("MATCH(artist_name) AGAINST( ? )", [$request->artist_name]);
                    } else {
                        $album->where('artist_name', 'LIKE', '%'.$request->artist_name.'%');
                    }
                    $album->orWhereIn('artist_id', $artists);
                });
            }

            // 詳細検索
            if ($request->filled('album_artist')) {
                $musics->whereHas('album', function($query) use($request) {
                    $album_artists = Artist::query();
                    if (mb_strlen($request->album_artist) > 2) {
                        $album_artists->whereRaw("MATCH(name) AGAINST( ? )", [$request->album_artist]);
                    } else {
                        $album_artists->where('name', 'LIKE', '%'.$request->album_artist.'%');
                    }
                    $album_artists = $album_artists->get()
                        ->pluck('id');
                    $query->where('artist_name', 'LIKE', '%'.$request->album_artist.'%')
                        ->orWhereRaw("MATCH(artist_name) AGAINST( ? )", [$request->album_artist])
                        ->orWhereIn('artist_id', $album_artists);
                });
            }

            if ($request->filled('music_artist') || $request->filled('music_part')) {
                $musics->whereHas('parts', function($parts) use($request) {
                    if ($request->filled('music_artist')) {
                        $music_artists = Artist::where('name', 'LIKE', '%'.$request->music_artist.'%')
                        ->orWhereRaw("MATCH(name) AGAINST( ? )", [$request->music_artist])
                        ->get()
                        ->pluck('id');
                        $parts->where(function($group) use($request, $music_artists) {
                            if (mb_strlen($request->music_artist) > 2) {
                                $group->whereRaw("MATCH(artist_name) AGAINST( ? )", [$request->music_artist]);
                            } else {
                                $group->where('artist_name', 'LIKE', '%'.$request->music_artist.'%');
                            }
                            $group->orWhereIn('artist_id', $music_artists);
                        });
                    }
                    if ($request->filled('music_part')) {
                        if (mb_strlen($request->music_part) > 2) {
                            $parts->whereRaw("MATCH(part_name) AGAINST( ? )", [$request->music_part]);
                        } else {
                            $parts->where('part_name', 'LIKE', '%'.$request->music_part.'%');
                        }
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

        return view('search.music.album', $params);
    }
}
