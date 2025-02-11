<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use function env;

class FocalPersonInvitationToBreederEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
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
        // Generate a temporary password
        $temporaryPassword = Str::random(12);

        // Generate a signed URL with an expiration time
        $url = URL::temporarySignedRoute(
            'accept.breeder.role',
            now()->addMinutes(60),
            ['user' => $notifiable->id]
        );

        // Update the user's password (hashing it before saving)
        $notifiable->update(['password' => Hash::make($temporaryPassword)]);

        return (new MailMessage)
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Breeder Role Invitation in ' . env('APP_NAME') . ' (' . env('APP_NAME_SHORT') . ') System')
            ->greeting('Hi, ' . $this->getPersonName($notifiable) . '!')
            ->line("You have been invited to use the " . env('APP_NAME') . ' (' . env('APP_NAME_SHORT') . ') System developed by ' . env('COMPANY_NAME') . '.')
            ->line('You were invited to be a Breeder.')
            ->line('Here are your credentials: (Do not share)')
            ->line('Email: ' . $notifiable->email)
            ->line('Temporary Password: ' . $temporaryPassword)
            ->line('Please click the button below to accept the invitation. This invitation will expire in 60 minutes.')
            ->action('Accept Breeder Role', $url)
            ->line('Have a nice day!');
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Breeder Role Invitation in ' . env('APP_NAME') . ' (' . env('APP_NAME_SHORT') . ')',
        ];
    }

    private function getPersonName(object $notifiable): string
    {
        return $notifiable->getFullName();
    }

}
