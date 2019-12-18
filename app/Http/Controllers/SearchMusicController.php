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

                // $musics->whereHas('album', function($query) use($request){
                //     if (mb_strlen($request->album_title) > 2) {
                //         $query->whereRaw("MATCH(title) AGAINST( ? )", [$request->album_title]);
                //     } else {
                //         $query->where('title', 'LIKE', '%'.$request->album_title.'%');
                //     }
                // });
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

                // $musics->where(function($query) use($request) {
                //     if (mb_strlen($request->music_title) > 2) {
                //         $query->whereRaw("MATCH(title) AGAINST( ? )", [$request->music_title]);
                //     } else {
                //         $query->where('title', 'LIKE', '%'.$request->music_title.'%');
                //     }
                // });

            }

            if ($request->filled('artist_name')) {
                $artists_ids = LocaleName::select('artist_id')
                    ->whereNotNull('artist_id');
                if (mb_strlen($request->artist_name) > 2) {
                    $artists_ids->whereRaw("MATCH(name) AGAINST( ? )", [$request->artist_name]);
                } else {
                    $artists_ids->where('name', 'LIKE', '%'.$request->artist_name.'%');
                }
                $artists_ids = $artists_ids->get()
                    ->pluck('artist_id');
                $musics->whereIn('artist_id', $artists_ids)
                    ->orWhereIn('album_id', functioN($query) use($artists_ids) {
                        $query->form('albums')
                            ->select('albums.id')
                            ->whereIn('albums.artist_id', $artists_ids);
                    });

                // $artists = Artist::query();
                // if (mb_strlen($request->artist_name) > 2) {
                //     $artists->whereRaw("MATCH(name) AGAINST( ? )", [$request->artist_name]);
                // } else {
                //     $artists->where('name', 'LIKE', '%'.$request->artist_name.'%');
                // }
                // $artists = $artists->get()
                //     ->pluck('id');
                // $musics->whereHas('parts', function($parts) use($request, $artists) {
                //     if (mb_strlen($request->artist_name) > 2) {
                //         $parts->whereRaw("MATCH(artist_name) AGAINST( ? )", [$request->artist_name]);
                //     } else {
                //         $parts->where('artist_name', 'LIKE', '%'.$request->artist_name.'%');
                //     }
                //     $parts->orWhereIn('artist_id', $artists);
                // })
                // ->orWhereHas('album', function($album) use($request, $artists) {
                //     if (mb_strlen($request->artist_name) > 2) {
                //         $album->whereRaw("MATCH(artist_name) AGAINST( ? )", [$request->artist_name]);
                //     } else {
                //         $album->where('artist_name', 'LIKE', '%'.$request->artist_name.'%');
                //     }
                //     $album->orWhereIn('artist_id', $artists);
                // });
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

                // $musics->whereHas('album', function($query) use($request) {
                //     $album_artists = Artist::query();
                //     if (mb_strlen($request->album_artist) > 2) {
                //         $album_artists->whereRaw("MATCH(name) AGAINST( ? )", [$request->album_artist]);
                //     } else {
                //         $album_artists->where('name', 'LIKE', '%'.$request->album_artist.'%');
                //     }
                //     $album_artists = $album_artists->get()
                //         ->pluck('id');
                //     $query->where('artist_name', 'LIKE', '%'.$request->album_artist.'%')
                //         ->orWhereRaw("MATCH(artist_name) AGAINST( ? )", [$request->album_artist])
                //         ->orWhereIn('artist_id', $album_artists);
                // });
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
                            $query2->from('locale_name')
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

                // $musics->whereHas('parts', function($parts) use($request) {
                //     if ($request->filled('music_artist')) {
                //         $music_artists = Artist::where('name', 'LIKE', '%'.$request->music_artist.'%')
                //         ->orWhereRaw("MATCH(name) AGAINST( ? )", [$request->music_artist])
                //         ->get()
                //         ->pluck('id');
                //         $parts->where(function($group) use($request, $music_artists) {
                //             if (mb_strlen($request->music_artist) > 2) {
                //                 $group->whereRaw("MATCH(artist_name) AGAINST( ? )", [$request->music_artist]);
                //             } else {
                //                 $group->where('artist_name', 'LIKE', '%'.$request->music_artist.'%');
                //             }
                //             $group->orWhereIn('artist_id', $music_artists);
                //         });
                //     }
                //     if ($request->filled('music_part')) {
                //         if (mb_strlen($request->music_part) > 2) {
                //             $parts->whereRaw("MATCH(name) AGAINST( ? )", [$request->music_part]);
                //         } else {
                //             $parts->where('name', 'LIKE', '%'.$request->music_part.'%');
                //         }
                //     }
                // });
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
