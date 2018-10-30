<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateConfirmResponsibility extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var array
     */
    public $mailData;

    /**
     * Create a new message instance.
     *
     * @param $mailData
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from($this->mailData['from'])
            ->subject($this->mailData['subject'])
            ->view('email_template.progcorp_update')
            ->with(['data' => $this->mailData]);
    }
}
