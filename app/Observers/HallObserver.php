<?php

namespace App\Observers;

use App\Models\Hall;

class HallObserver
{
  /**
   * Handle the Hall "created" event.
   */
  public function created(Hall $hall): void
  {
    //
  }

  public function updating(Hall $hall)
  {
    // Store previous states before deactivation
    if ($hall->isDirty('status') && $hall->status == false) {
      // Store room states in temporary table or cache
      $hall->rooms->each(function($room) {
        cache()->put("room_state_{$room->id}", $room->status, now()->addDay());
      });
    }
  }

  public function updated(Hall $hall)
  {
    if ($hall->isDirty('status') || $hall->isDirty('deleted_at')) {
      if (!$hall->status || $hall->deleted_at) {
        // Deactivate all rooms and their seats
        $hall->rooms()->update(['status' => false]);
        foreach ($hall->rooms as $room) {
          $room->seats()->update(['status' => false]);
        }
      } else {
        // Hall became active - restore previous room states
        $hall->rooms->each(function($room) {
          $previousState = cache()->get("room_state_{$room->id}", false);
          $room->update(['status' => $previousState]);
          cache()->forget("room_state_{$room->id}");
        });
      }
    }
  }

  /**
   * Handle before the Hall is deleted
   */
  public function deleting(Hall $hall)
  {
    // Deactivate rooms and seats before deletion
    $hall->rooms()->update(['status' => false]);
    foreach ($hall->rooms as $room) {
      $room->seats()->update(['status' => false]);
    }
  }

  /**
   * Handle the Hall "deleted" event.
   */
  public function deleted(Hall $hall): void
  {
    //
  }

  /**
   * Handle the Hall "restored" event.
   */
  public function restored(Hall $hall): void
  {
    //
  }

  /**
   * Handle the Hall "force deleted" event.
   */
  public function forceDeleted(Hall $hall): void
  {
    //
  }
}
