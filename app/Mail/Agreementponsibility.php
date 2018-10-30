<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Agreementponsibility extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var array
     */
    public $mailData;
    /**
     * @var string
     */
    public $corpName;

    /**
     * Create a new message instance.
     *
     * @param $mailData
     * @param $corpName
     */
    public function __construct($mailData, $corpName)
    {
        $this->mailData = $mailData;
        $this->corpName = $corpName;
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
            ->view('email_template.aff_agreement_responsibility')
            ->with(['corpName' => $this->corpName]);
    }
}
