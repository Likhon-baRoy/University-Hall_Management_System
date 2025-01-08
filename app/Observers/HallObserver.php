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

  /**
   * Handle status changes and soft deletes
   */
  public function updated(Hall $hall)
  {
    // If hall status changed to false or hall is soft deleted
    if ($hall->isDirty('status') || $hall->isDirty('deleted_at')) {
      // Deactivate all rooms under this hall
      $hall->rooms()->update([
        'status' => false
      ]);

      // Deactivate all seats under those rooms
      foreach ($hall->rooms as $room) {
        $room->seats()->update([
          'status' => false
        ]);
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
