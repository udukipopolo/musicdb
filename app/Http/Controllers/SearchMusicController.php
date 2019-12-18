<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\LocaleName;
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
                $musics->whereIn('album_id', function($query) use($request) {
                    $query->from('locale_names')
                        ->select('locale_names.localable_id')
                        ->where('locale_names.localable_type', 'albums');
                    if (mb_strlen($request->album_title) > 2) {
                        $query->whereRaw("MATCH(locale_names.name) AGAINST( ? )", [$request->album_title]);
                    } else {
                        $query->where('locale_names.name', 'LIKE', '%'.$request->album_title.'%');
                    }
                });

            }

            if ($request->filled('music_title')) {
                $musics->whereIn('id', function($query) use($request) {
                    $query->from('locale_names')
                        ->select('locale_names.localable_id')
                        ->where('locale_names.localable_type', 'musics');
                    if (mb_strlen($request->music_title) > 2) {
                        $query->whereRaw("MATCH(locale_names.name) AGAINST( ? )", [$request->music_title]);
                    } else {
                        $query->where('locale_names.name', 'LIKE', '%'.$request->music_title.'%');
                    }
                });

            }

            if ($request->filled('artist_name')) {
                $artists_ids = LocaleName::distinct()
                    ->select('artist_id')
                    ->whereNotNull('artist_id');
                if (mb_strlen($request->artist_name) > 2) {
                    $artists_ids->whereRaw("MATCH(name) AGAINST( ? )", [$request->artist_name]);
                } else {
                    $artists_ids->where('name', 'LIKE', '%'.$request->artist_name.'%');
                }
                $artists_ids = $artists_ids->get()
                    ->pluck('artist_id');
                $musics->whereIn('id', functioN($query) use($artists_ids) {
                        $query->from('parts')
                            ->select('parts.music_id')
                            ->whereIn('parts.artist_id', $artists_ids);
                    })
                    ->orWhereIn('album_id', functioN($query) use($artists_ids) {
                        $query->from('albums')
                            ->select('albums.id')
                            ->whereIn('albums.artist_id', $artists_ids);
                    });

            }

            // 詳細検索
            if ($request->filled('album_artist')) {
                $musics->whereIn('album_id', function($query) use($request) {
                    $query->from('albums')
                        ->select('albums.id')
                        ->whereIn('albums.artist_id', function($query2) use($request) {
                            $query2->from('locale_names')
                                ->select('locale_names.artist_id')
                                ->whereNotNull('locale_names.artist_id');
                            if (mb_strlen($request->album_artist) > 2) {
                                $query2->whereRaw("MATCH(locale_names.name) AGAINST( ? )", [$request->album_artist]);
                            } else {
                                $query2->where('locale_names.name', 'LIKE', '%'.$request->album_artist.'%');
                            }

                        });
                });

            }

            if ($request->filled('music_artist') || $request->filled('music_part')) {
                $musics->whereIn('id', function($query) use($request) {
                    $query->from('parts')
                        ->select('parts.music_id');
                    if ($request->filled('music_artist')) {
                        $query->whereIn('parts.artist_id', function($query2) use($request) {
                            $query2->from('locale_names')
                                ->select('locale_names.artist_id')
                                ->whereNotNull('locale_names.artist_id');
                            if (mb_strlen($request->album_artmusic_artistist) > 2) {
                                $query2->whereRaw("MATCH(locale_names.name) AGAINST( ? )", [$request->music_artist]);
                            } else {
                                $query2->where('locale_names.name', 'LIKE', '%'.$request->music_artist.'%');
                            }
                        });
                    }
                    if ($request->filled('music_part')) {
                        $query->whereIn('parts.id', function($query2) use($request) {
                            $query2->from('locale_names')
                                ->select('locale_names.localable_id')
                                ->where('locale_names.localable_type', 'parts');
                            if (mb_strlen($request->music_part) > 2) {
                                $query2->whereRaw("MATCH(locale_names.name) AGAINST( ? )", [$request->music_part]);
                            } else {
                                $query2->where('locale_names.name', 'LIKE', '%'.$request->music_part.'%');
                            }
                        });
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
