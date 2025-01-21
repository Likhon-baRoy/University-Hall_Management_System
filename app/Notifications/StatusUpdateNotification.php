<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class StatusUpdateNotification extends Notification
{
  use Queueable;

  protected $status;
  protected $userName;

  public function __construct($status, $userName)
  {
    $this->status = $status;
    $this->userName = $userName;
  }

  public function via($notifiable)
  {
    return ['mail'];
  }

  public function toMail($notifiable)
  {
    $status = $this->status ? 'activated' : 'deactivated';

    return (new MailMessage)
            ->subject('Account Status Update')
            ->greeting('Hello ' . $this->userName)
            ->line('Your account status has been updated.')
            ->line('Your account is now ' . $status . '.')
            ->line('If you did not expect this change, please contact our support team.')
            ->action('View Account', url('/admin-dashboard'))
            ->line('Thank you for using our application!');
  }
}
