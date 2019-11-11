<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
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
            ],
            [],
            [
                'title' => 'アルバムタイトル',
                'artist_id' => '',
                'artist_name' => 'アーティスト名',
            ]
        );

        return redirect()->route('manage.album.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        //
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
        //
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
}
