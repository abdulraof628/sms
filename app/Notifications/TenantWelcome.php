<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantWelcome extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected string $password,
        protected string $tenantName = ''
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
            ->subject('Welcome to ' . config('app.name') . ' – Your Account is Ready! 🎉')
            ->view('emails.tenant-welcome', [
                'tenantName' => $this->tenantName ?: $notifiable->name,
                'email' => $notifiable->email,
                'password' => $this->password,
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
            'tenant_name' => $this->tenantName,
            'email' => $notifiable->email,
            'sent_at' => now()->toDateTimeString(),
        ];
    }
} 