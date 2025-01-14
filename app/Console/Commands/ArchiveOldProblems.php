<?php

namespace App\Console\Commands;

use App\Models\Problem;
use Illuminate\Console\Command;

class ArchiveOldProblems extends Command
{
  protected $signature = 'problems:archive';
  protected $description = 'Archive old resolved/closed problems';

  public function handle()
  {
    Problem::resolved()
           ->where('updated_at', '<', now()->subDays(30))
           ->each(function ($problem) {
             $problem->delete();
             $this->info("Archived problem #{$problem->id}");
           });

    $this->info('Archive process completed.');
  }
}
