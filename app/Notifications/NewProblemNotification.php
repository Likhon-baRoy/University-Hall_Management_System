<?php
// app/Notifications/NewProblemNotification.php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Problem;

class NewProblemNotification extends Notification
{
protected $problem;

public function __construct(Problem $problem)
{
$this->problem = $problem;
}

public function via($notifiable)
{
return ['database'];
}

public function toDatabase($notifiable)
{
return [
'problem_id' => $this->problem->id,
'title' => $this->problem->title,
'message' => "New problem reported",
'user_name' => $this->problem->user->name,
'priority' => $this->problem->priority,
'created_at' => now(),
];
}
}
