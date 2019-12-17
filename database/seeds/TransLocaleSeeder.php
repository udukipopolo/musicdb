<?php

use App\Models\Album;
use App\Models\Artist;
use App\Models\Music;
use App\Models\Part;
use Illuminate\Database\Seeder;

class TransLocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locale_names')->truncate();
        DB::table('locale_texts')->truncate();

        $artists = Artist::all();
        foreach($artists as $artist) {
            $artist->artist_name()->create([
                'column' => 'artist_name',
                'locale' => 'ja',
                'name' => $artist->name,
            ]);
            $artist->artist_belonging()->create([
                'column' => 'belonging',
                'locale' => 'ja',
                'text' => $artist->belonging,
            ]);
        }

        $albums = Album::all();
        foreach($albums as $album) {
            $album->album_title()->create([
                'column' => 'title',
                'locale' => 'ja',
                'name' => $album->title,
            ]);
            $album->album_artist_name()->create([
                'column' => 'artist_name',
                'locale' => 'ja',
                'name' => $album->artist_name,
            ]);
            $album->album_description()->create([
                'column' => 'description',
                'locale' => 'ja',
                'text' => $album->description,
            ]);
        }

        $musics = Music::all();
        foreach($musics as $music) {
            $music->music_title()->create([
                'column' => 'title',
                'locale' => 'ja',
                'name' => $music->title,
            ]);
        }

        $parts = Part::all();
        foreach($parts as $part) {
            $part->part_artist_name()->create([
                'column' => 'artist_name',
                'locale' => 'ja',
                'name' => $part->artist_name,
            ]);
            $part->part_name()->create([
                'column' => 'name',
                'locale' => 'ja',
                'name' => $part->name,
            ]);
        }
    }
}
