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

  /**
   * Handle status changes and soft deletes
   */
  public function updated(Room $room)
  {
    // If room status changed to false or room is soft deleted
    if ($room->isDirty('status') || $room->isDirty('deleted_at')) {
      // Deactivate all seats under this room
      $room->seats()->update([
        'status' => false
      ]);
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
