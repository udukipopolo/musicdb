<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\Request;

class SearchMusicController extends Controller
{
    public function index(Request $request)
    {
        $params = [];

        $input = $request->input();
        $params['input'] = $input;

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
            $musics->whereHas('parts', function($parts) use($request) {
                $parts->where('artist_name', 'LIKE', '%'.$request->artist_name.'%')
                    ->orWhereHas('artist', function($artist) use($request) {
                        $artist->where('name', 'LIKE', '%'.$request->artist_name.'%');
                    });
            });
        }

        $params['musics'] = $musics->paginate(20);

        return view('search.music.index', $params);
    }

    public function show(Music $music)
    {

    }
}
