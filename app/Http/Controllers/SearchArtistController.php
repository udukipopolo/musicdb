<?php

namespace App\Http\Controllers;

use App\Models\Artist;
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
                $artists->where('name', 'LIKE', '%'.$request->artist_name.'%')
                    ->orWhereHas('parts', function($parts) use($request) {
                        $parts->where('artist_name', 'LIKE', '%'.$request->artist_name.'%');
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
}
