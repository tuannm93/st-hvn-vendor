<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FaxEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param $mailData
     * @param $data
     * @param $header
     */
    public function __construct($mailData, $data)
    {
        $this->mailData = $mailData;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('email_template.fax_body')
            ->from($this->mailData['from'])
            ->subject($this->mailData['subject'])
            ->bcc($this->mailData['bcc'])
            ->attach($this->data['filebase'])
            ->with(['data' => $this->data]);
    }
}
