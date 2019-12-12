<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Music;
use Illuminate\Http\Request;

class SearchArtistController extends Controller
{
    public function index(Request $request)
    {
        $params = [];
        $input = $request->input();
        $params['input'] = $input;

        if (count($request->query()) > 0) {
            $artists = Artist::query();

            if ($request->filled('artist_name')) {
                $artists->whereRaw("MATCH(name) AGAINST( ? )", [$request->artist_name])
                    //->where('name', 'LIKE', '%'.$request->artist_name.'%')
                    ->orWhereHas('parts', function($parts) use($request) {
                        $parts->whereRaw("MATCH(artist_name) AGAINST( ? )",[$request->artist_name]);
                            //->where('artist_name', 'LIKE', '%'.$request->artist_name.'%');
                    });
            }

            $artists->orderBy('name', 'ASC');

            $params['artists'] = $artists->paginate(50);
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
