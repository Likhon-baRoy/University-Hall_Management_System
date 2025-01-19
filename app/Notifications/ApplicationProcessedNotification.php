<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\SeatApplication;

class ApplicationProcessedNotification extends Notification
{
  use Queueable;

  private $application;

  public function __construct(SeatApplication $application)
  {
    $this->application = $application;
  }

  // Define the notification delivery channels
  public function via($notifiable)
  {
    return ['mail']; // Add additional channels like 'database' if needed
  }

  public function toMail($notifiable)
  {
    $status = ucfirst($this->application->status);
    $type = ucfirst($this->application->application_type);

    return (new MailMessage)
            ->subject("Seat {$type} Application {$status}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your seat {$this->application->application_type} application has been {$this->application->status}.")
            ->when($this->application->admin_note, function ($mail) {
              return $mail->line("Admin Note: {$this->application->admin_note}");
            })
            ->when($this->application->status === 'approved', function ($mail) {
              if ($this->application->application_type === 'change') {
                return $mail->line("Your new seat assignment: {$this->application->requestedSeat->room->hall->name} - Room {$this->application->requestedSeat->room->name} - Seat {$this->application->requestedSeat->name}");
              } else {
                return $mail->line("Your seat has been successfully cancelled.");
              }
            })
            ->action('View Application Details', route('applications.show', $this->application))
            ->line('Thank you for using our service.');
  }
}
