<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Music;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $params = [];

        if ($request->filled('q')) {
            $params['input'] = $request->input();

            // 楽曲検索
            $musics = Music::query();
            $musics->whereIn('id', function($query) use($request) {
                $query->from('locale_names')
                    ->distinct()
                    ->select('locale_names.localable_id')
                    ->where('locale_names.localable_type', 'musics');
                $query->whereRaw("MATCH(locale_names.name) AGAINST( ? )", [$request->q]);
            });
            $params['musics'] = $musics->take(5)->get();

            // アーティスト検索
            $artists = Artist::query();
            $artists->distinct()
                ->select([
                    'artists.*',
                ]);
            $artists->join('locale_names', function($join) {
                $join->on('locale_names.artist_id', '=', 'artists.id')
                    ->whereRaw("locale_names.column = 'name'");
            });
            $artists->addSelect(\DB::raw("MAX(MATCH(locale_names.name) AGAINST( ?  IN NATURAL LANGUAGE MODE)) AS score"));
            $artists->whereRaw("MATCH(locale_names.name) AGAINST( ?  IN NATURAL LANGUAGE MODE)");
            $artists->groupBy('artists.id');
            $artists->orderBy('score', 'DESC');
            $artists->setBindings([$request->q, $request->q]);
            $params['artists'] = $artists->take(5)->get();
        }


        return view('home.index', $params);
    }

    public function faq()
    {
        return view('home.faq');
    }
}
