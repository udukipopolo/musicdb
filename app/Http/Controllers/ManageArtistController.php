<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManageArtistController extends Controller
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

            $artists = Artist::query();
            if ($request->filled('name')) {
                $artists->where('name', 'LIKE', '%'.$request->name.'%');
            }
            $artists->orderBy('name', 'ASC');
            $params['artists'] = $artists->paginate(50);
        }

        return view('manage.artist.index', $params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params = [];

        return view('manage.artist.create', $params);
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
                'artist_name' => 'required|max:255|unique:artists,name',
                'belonging' => '',
            ],
            [],
            [
                'artist_name' => 'アーティスト名',
                'belonging' => '所属事務所',
            ]
        );

        $artist = Artist::create([
            'name' => $request->artist_name,
            'belonging' => ($request->filled('belonging')) ? $request->input('belonging') : '',
        ]);

        return redirect()->route('manage.artist.index')->with('message', 'アーティストを登録しました。');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function show(Artist $artist)
    {
        $params = [
            'artist' => $artist,
        ];

        return view('manage.artist.show', $params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function edit(Artist $artist)
    {
        $params = [
            'artist' => $artist,
        ];

        return view('manage.artist.edit', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Artist $artist)
    {
        $request->validate(
            [
                'artist_name' => [
                    'required',
                    'max:255',
                    Rule::unique('artists', 'name')->ignore($artist->id),
                ],
                'belonging' => '',
            ],
            [],
            [
                'artist_name' => 'アーティスト名',
                'belonging' => '所属事務所',
            ]
        );

        $artist->name = $request->artist_name;
        $artist->belonging = ($request->filled('belonging')) ? $request->input('belonging') : '';
        $artist->save();

        return redirect()->route('manage.artist.show', $artist->id)->with('message', '更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Artist $artist)
    {
        $artist->parts()->dissociate();
        $artist->albums()->dissociate();
        $artist->delete();

        return redirect()->route('manage.artist.index')->with('message', 'アーティストを削除しました。');
    }

    public function apiArtistList(Request $request)
    {
        $artists = Artist::query();
        $artists->select(['name', 'id']);
        if ($request->filled('q')) {
            $artists->where('name', 'LIKE', '%'.$request->q.'%');
        }
        return $artists->get()->toJson();
    }
}
