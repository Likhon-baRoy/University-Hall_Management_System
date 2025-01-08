<?php

namespace App\Observers;

use App\Models\Room;

class RoomObserver
{
  /**
   * Handle the Room "created" event.
   */
  public function created(Room $room): void
  {
    //
  }

  public function updating(Room $room)
  {
    // Store previous states before deactivation
    if ($room->isDirty('status') && $room->status == false) {
      $room->seats->each(function($seat) {
        cache()->put("seat_state_{$seat->id}", $seat->status, now()->addDay());
      });
    }
  }

  public function updated(Room $room)
  {
    // If room status changed or room is soft deleted
    if ($room->isDirty('status') || $room->isDirty('deleted_at')) {
      // If room is inactive or deleted, deactivate all seats
      if (!$room->status || $room->deleted_at) {
        // Deactivate all seats
        $room->seats()->update(['status' => false]);
      } else {
        // Room became active - only restore seats if hall is also active
        if ($room->hall->status && !$room->hall->deleted_at) {
          $room->seats->each(function($seat) {
            $previousState = cache()->get("seat_state_{$seat->id}", false);
            $seat->update(['status' => $previousState]);
            cache()->forget("seat_state_{$seat->id}");
          });
        }
      }
    }
  }

  /**
   * Handle before the Room is deleted
   */
  public function deleting(Room $room)
  {
    // Deactivate all seats before deletion
    $room->seats()->update(['status' => false]);
  }

  /**
   * Handle the Room "deleted" event.
   */
  public function deleted(Room $room): void
  {
    //
  }

  /**
   * Handle the Room "restored" event.
   */
  public function restored(Room $room): void
  {
    //
  }

  /**
   * Handle the Room "force deleted" event.
   */
  public function forceDeleted(Room $room): void
  {
    //
  }
}
