<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // リレーション
        Relation::morphMap([
            'artists' => 'App\Models\Artist',
            'albums' => 'App\Models\Album',
            'musics' => 'App\Models\Music',
            'parts' => 'App\Models\Part',
        ]);
    }
}
