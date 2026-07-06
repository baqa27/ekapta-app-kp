<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->details['subject'] ?? 'Notifikasi EKAPTA';
        
        // Pastikan title ada untuk view
        if (!isset($this->details['title'])) {
            $this->details['title'] = $subject;
        }
        
        return $this->subject($subject)
            ->view('emails.notification');
    }
}
