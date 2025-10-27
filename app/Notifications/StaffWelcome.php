<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffWelcome extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string $employeeId,
        protected string $staffName = '',
        protected ?string $position = null,
        protected ?string $department = null,
        protected ?string $password = null,
        protected ?string $email = null
    ) {
        //
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
            ->subject('Welcome to the Team – Your Account is Ready! 🎉')
            ->view('emails.staff-welcome', [
                'staffName' => $this->staffName,
                'employeeId' => $this->employeeId,
                'email' => $this->email,
                'password' => $this->password,
                'position' => $this->position,
                'department' => $this->department,
                'loginUrl' => route('login'),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'staff_name' => $this->staffName,
            'employee_id' => $this->employeeId,
            'email' => $notifiable->email,
            'sent_at' => now()->toDateTimeString(),
        ];
    }
}
