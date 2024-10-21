<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public $otp;
    public $name;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $otp)
    {
        $this->name = $name;
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Permintaan mengganti password')
                    ->greeting('Hello ' . $this->name)
                    ->line('Silahkan gunakan OTP di bawah ini untuk mengganti password Anda.')
                    ->line('OTP: ' . $this->otp)
                    ->action('Ganti Password', url('/change_password'))
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }
}
