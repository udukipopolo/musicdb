<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\LocaleName;
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
                'artist_id' => 'required|max:255|unique:artists,name',
                'belonging' => '',
            ]
        );

        $artist = Artist::create([
            'name' => $request->artist_id,
            'belonging' => ($request->filled('belonging')) ? $request->input('belonging') : '',
        ]);
        $artist->locale_name()->updateOrCreate(
            [
                'column' => 'name',
                'locale' => 'ja',
            ],
            [
                'artist_id' => $artist->id,
                'name' => $request->artist_id,
            ]
        );
        $artist->locale_text()->updateOrCreate(
            [
                'column' => 'belonging',
                'locale' => 'ja',
            ],
            [
                'text' => ($request->filled('belonging')) ? $request->input('belonging') : '',
            ]
        );

        return redirect()->route('manage.artist.index')->with('message', __('messages.registered'));
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
                'artist_id' => [
                    'required',
                    'max:255',
                    Rule::unique('artists', 'name')->ignore($artist->id),
                ],
                'belonging' => '',
            ]
        );

        $artist->name = $request->artist_id;
        $artist->belonging = ($request->filled('belonging')) ? $request->input('belonging') : '';
        $artist->save();
        $artist->locale_name()->updateOrCreate(
            [
                'column' => 'name',
                'locale' => 'ja',
            ],
            [
                'artist_id' => $artist->id,
                'name' => $request->artist_id,
            ]
        );
        $artist->locale_text()->updateOrCreate(
            [
                'column' => 'belonging',
                'locale' => 'ja',
            ],
            [
                'text' => ($request->filled('belonging')) ? $request->input('belonging') : '',
            ]
        );

        return redirect()->route('manage.artist.show', $artist->id)->with('message', __('messages.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Artist $artist)
    {
        $artist->locale_name()->delete();
        $artist->locale_text()->delete();
        $artist->parts()->dissociate();
        $artist->albums()->dissociate();
        $artist->delete();

        return redirect()->route('manage.artist.index')->with('message', __('messages.deleted'));
    }

    public function apiArtistList(Request $request)
    {
        $artists = LocaleName::query();
        $artists->distinct()->select(['name', 'id']);
        $artists->where('localable_type', 'artists')
            ->where('locale', 'ja')
            ->where('column', 'name');
        if ($request->filled('q')) {
            $artists->where('name', 'LIKE', '%'.$request->q.'%');
        }
        return $artists->get()->toJson();
    }
}
