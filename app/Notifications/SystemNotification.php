<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /*
    |--------------------------------------------------------------------------
    | Panduan Konfigurasi Queue di Shared Hosting (cPanel)
    |--------------------------------------------------------------------------
    | Karena shared hosting jarang mengizinkan instalasi Supervisor, Anda bisa
    | menjalankan queue worker menggunakan fitur Cron Jobs.
    |
    | Tambahkan baris ini di Cron Jobs cPanel (set setiap 1 menit / * * * * *):
    |
    | /usr/local/bin/php /home/username/public_html/sehati/artisan queue:work --stop-when-empty
    |
    | Catatan: Sesuaikan '/usr/local/bin/php' dengan path PHP di server Anda,
    | dan sesuaikan path '/home/username/...' menuju direktori project Laravel.
    | Opsi --stop-when-empty memastikan cron tidak membuat proses menumpuk.
    */

    protected $title;
    protected $message;
    protected $url;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $url = '#')
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
        ];
    }
}
