<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProgCorpNotice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    public $mailData;
    /**
     * @var mixed
     */
    public $progCorpData;

    /**
     * Create a new message instance.
     *
     * @param $mailData
     * @param $progCorpData
     */
    public function __construct($mailData, $progCorpData)
    {
        $this->mailData = $mailData;
        $this->progCorpData = $progCorpData;
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
            ->view('email_template.progcorp_notice')
            ->with(['progCorpData' => $this->progCorpData]);
    }
}
