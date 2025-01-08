<?php

namespace App\Providers;

use App\Models\Hall;
use App\Models\Room;
use App\Observers\HallObserver;
use App\Observers\RoomObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
  /**
   * Register any events for your application.
   */
  public function boot(): void
  {
    Hall::observe(HallObserver::class);
    Room::observe(RoomObserver::class);
  }
}
