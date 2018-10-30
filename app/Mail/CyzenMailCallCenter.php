<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CyzenMailCallCenter extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var array
     */
    public $mailData;

    /**
     * Create a new message instance.
     *
     * @param array $mailData
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
        $this->mailData['content']['isCallCenter'] = true;
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
            ->view($this->mailData['template'])
            ->with([
                'content' => $this->mailData['content']
            ]);
    }
}
