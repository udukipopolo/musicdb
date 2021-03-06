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
            $artist->locale_name()->updateOrCreate(
                [
                    'column' => 'name',
                    'locale' => 'ja',
                ],
                [
                    'artist_id' => $artist->id,
                    'name' => $artist->name,
                ]
            );
            $artist->locale_text()->updateOrCreate(
                [
                    'column' => 'belonging',
                    'locale' => 'ja',
                ],
                [
                    'text' => $artist->belonging,
                ]
            );
        }

        $albums = Album::all();
        foreach($albums as $album) {
            $album->locale_name()->updateOrCreate(
                [
                    'column' => 'title',
                    'locale' => 'ja',
                ],
                [
                    'name' => $album->title,
                ]
            );
            $album->locale_name()->updateOrCreate(
                [
                    'column' => 'artist_name',
                    'locale' => 'ja',
                ],
                [
                    'artist_id' => $album->artist_id,
                    'name' => $album->artist_name,
                ]
            );
            $album->locale_text()->updateOrCreate(
                [
                    'column' => 'description',
                    'locale' => 'ja',
                ],
                [
                    'text' => $album->description,
                ]
            );
        }

        $musics = Music::all();
        foreach($musics as $music) {
            $music->locale_name()->updateOrCreate(
                [
                    'column' => 'title',
                    'locale' => 'ja',
                ],
                [
                    'name' => $music->title,
                ]
            );
        }

        $parts = Part::all();
        foreach($parts as $part) {
            $part->locale_name()->updateOrCreate(
                [
                    'column' => 'artist_name',
                    'locale' => 'ja',
                ],
                [
                    'artist_id' => $part->artist_id,
                    'name' => $part->artist_name,
                ]
            );
            $part->locale_name()->updateOrCreate(
                [
                    'column' => 'name',
                    'locale' => 'ja',
                ],
                [
                    'name' => $part->name,
                ]
            );
        }
    }
}
