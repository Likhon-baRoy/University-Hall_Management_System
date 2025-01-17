<?php
// app/Notifications/ProblemResponseNotification.php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Problem;

class ProblemResponseNotification extends Notification
{
  protected $problem;
  protected $response;

  public function __construct(Problem $problem, string $response)
  {
    $this->problem = $problem;
    $this->response = $response;
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
      'message' => "Admin responded to your problem",
      'response' => $this->response,
      'admin_name' => auth()->guard('admin')->user()->name,
      'created_at' => now(),
    ];
  }
}
