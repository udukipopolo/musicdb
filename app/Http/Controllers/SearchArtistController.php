<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Music;
use App\Traits\PaginatorTrait;
use Illuminate\Http\Request;

class SearchArtistController extends Controller
{
    use PaginatorTrait;

    public function index(Request $request)
    {
        $params = [];
        $input = $request->input();
        $params['input'] = $input;

        if (count($request->query()) > 0) {
            $artists = Artist::query();
            $artists->distinct()
                ->select([
                    'artists.*',
                ]);
            if ($request->filled('artist_name')) {
                $artists->join('locale_names', function($join) {
                    $join->on('locale_names.artist_id', '=', 'artists.id')
                        ->whereRaw("locale_names.column = 'name'");
                });
                if ($request->input('search_type') == 'like') {
                    $artists->where('locale_names.name', 'LIKE', '%'.$request->artist_name.'%');
                    $artists->groupBy('artists.id');
                    $artists->orderBy('artists.name', 'ASC');
                } else {
                    $artists->addSelect(\DB::raw("MAX(MATCH(locale_names.name) AGAINST( ?  IN NATURAL LANGUAGE MODE)) AS score"));
                    $artists->whereRaw("MATCH(locale_names.name) AGAINST( ?  IN NATURAL LANGUAGE MODE)");
                    $artists->groupBy('artists.id');
                    $artists->orderBy('score', 'DESC');
                    $artists->setBindings([$request->artist_name, $request->artist_name]);
                }
            } else {
                $artists->orderBy('artists.name', 'ASC');
            }

            // $artists->join('locale_names', function($join) {
            //     $join->on('locale_names.localable_id', '=', 'artists.id')
            //         ->where('locale_names.localable_type', '=', 'artists')
            //         ->where('locale_names.column', '=', 'name')
            //         ->where('locale_names.locale', '=', 'ja');
            // });
            // $artists->select([
            //     'artists.*',
            // ]);

            // if ($request->filled('artist_name')) {
            //     $artists->whereIn('artists.id', function($query) use($request) {
            //         $query->from('locale_names')
            //             ->distinct()
            //             ->select('locale_names.artist_id')
            //             ->whereNotNull('artist_id');
            //             if ($request->input('search_type') == 'like') {
            //                 $query->where('locale_names.name', 'LIKE', '%'.$request->artist_name.'%');
            //             } else {
            //                 $query->whereRaw("MATCH(locale_names.name) AGAINST( ? )", [$request->artist_name]);
            //             }
            //     });

            // }

            // $artists->orderBy('locale_names.name', 'ASC');

            $params['artists'] = $this->paginate($artists, 50, ['artists.id']);
        }

        return view('search.artist.index', $params);
    }

    public function show(Artist $artist)
    {
        $params = [
            'artist' => $artist,
            'parts' => $artist->parts()->paginate(20),
        ];

        return view('search.artist.show', $params);
    }

    public function showAlbumArtist(Artist $artist)
    {
        $params = [
            'artist' => $artist,
        ];

        $musics = Music::whereHas('album', functioN($query) use($artist) {
            $query->where('artist_id', $artist->id);
        })
        ->orderBy('album_id', 'ASC')
        ->orderBy('track_no', 'ASC')
        ->paginate(20);
        $params['musics'] = $musics;

        return view('search.artist.album_artist', $params);
    }
}
