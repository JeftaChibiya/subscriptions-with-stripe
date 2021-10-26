<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserSubscribed extends Notification
{
    use Queueable;

    public $invoice;

    public $invoiceId;

    public $yearMonth;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invoice, $invoiceId, $yearMonth)
    {
        $this->invoice = $invoice;
        $this->invoiceId = $invoiceId;
        $this->yearMonth = $yearMonth;
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
        $url = url('/invoice/' . $this->invoice->id);

        return (new MailMessage)
                    ->from('welcome@breezeapp.com', 'BreezeApp')
                    ->subject('Your Payment Was Received')
                    ->attachData($this->invoice, 'BreezeApp Invoice ' . $this->yearMonth . $this->invoiceId . '.pdf', [
                        'mime' => 'application/pdf',
                    ])
                    ->greeting('Hey!')
                    ->line('Thank you for your purchase.')
                    ->action('View Invoice', $url)
                    ->line("We're glad to have you onboard")
                    ->line('Kind Regards')
                    ->line('Breeze Team');
    }
}
