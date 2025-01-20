<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class ProblemResponseNotification extends Notification
{
  use Queueable;

  protected $problem;
  protected $response;
  protected $adminName;

  public function __construct($problem, $response, $adminName)
  {
    $this->problem = $problem;
    $this->response = $response;
    $this->adminName = $adminName;
  }

  public function via($notifiable)
  {
    return ['mail', 'database'];
  }

  /**
   * Get the mail representation of the notification.
   */
  public function toMail($notifiable)
  {
    return (new MailMessage)
            ->line('Hi ' . $notifiable->name . ',')
            ->line('Your problem post "' . $this->problem->title . '" received a response from the admin.')
            ->line('Response: "' . $this->response . '"')
            ->line('Thank you for using our application!');
  }

  public function toDatabase($notifiable)
  {
    return [
      'problem_id' => $this->problem->id,
      'title' => $this->problem->title,
      'message' => "Admin responded to your problem",
      'response' => $this->response,
      'admin_name' => $this->adminName,
      'created_at' => now(),
    ];
  }
}
