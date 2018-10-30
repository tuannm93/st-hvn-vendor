<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CorpResponsibility extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var array
     */
    public $mailData;
    /**
     * @var mixed
     */
    public $corp;

    /**
     * Create a new message instance.
     *
     * @param $mailData
     * @param $corp
     */
    public function __construct($mailData, $corp)
    {
        $this->mailData = $mailData;
        $this->corp = $corp;
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
            ->view('email_template.corp_responsibility')
            ->with(['corp' => $this->corp]);
    }
}
