<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use App\Models\Room;
use App\Observers\RoomObserver;
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
        Room::observe(RoomObserver::class);
    }
}
