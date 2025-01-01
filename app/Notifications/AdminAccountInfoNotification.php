<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminAccountInfoNotification extends Notification
{
  use Queueable;

  private $name;
  private $password;

  /**
   * Create a new notification instance.
   */
  public function __construct($data) {
    // get user password and name from given $data
    $this -> name = $data[0];
    $this -> password = $data[1];
  }

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function via(object $notifiable): array
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   */
  public function toMail(object $notifiable): MailMessage
  {
    return (new MailMessage)
                    ->line('Hi ' . $this -> name . ', welcome to City University Dormitory.')
                    ->line('Your account password is: ' . $this -> password)
                    ->line('Thank you for using our application!');
  }

  /**
   * Get the array representation of the notification.
   *
   * @return array<string, mixed>
   */
  public function toArray(object $notifiable): array
  {
    return [
      //
    ];
  }
}
