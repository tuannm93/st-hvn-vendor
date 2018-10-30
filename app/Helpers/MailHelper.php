<?php

namespace App\Helpers;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class MailHelper
{
    /**
     * @param array|string $toData
     * @param Mailable     $mailObject
     * @return mixed
     */
    public static function sendMail($toData, $mailObject)
    {
        return Mail::to($toData)->send($mailObject);
    }

    /**
     * @param $body
     * @param $subject
     * @param $from
     * @param $to
     * @param null $bcc
     */
    public static function sendRawMail($body, $subject, $from, $to, $bcc = null)
    {
        Mail::raw(
            $body,
            function ($message) use ($subject, $from, $to, $bcc) {
                $message->subject($subject);
                $message->to($to);
                $message->from($from);
                if ($bcc) {
                    $message->bcc($bcc);
                }
            }
        );
    }

    /**
     * @param $from
     * @param $to
     * @param $subject
     * @param $body
     * @param $attachs
     */
    public static function sendAttachMail($from, $to, $subject, $body, $attachs, $bcc = null)
    {
        Mail::raw(
            $body,
            function ($message) use ($from, $to, $subject, $attachs, $bcc) {
                $message->from($from);
                $message->to($to);
                $message->subject($subject);
                if ($bcc) {
                    $message->bcc($bcc);
                }
                foreach ($attachs as $file) {
                    $message->attach($file);
                }
            }
        );
    }

    /**
     * @param $template
     * @param $information
     * @param $data
     */
    public static function sendTemplateMail($template, $information, $data)
    {
        Mail::queue(
            $template,
            $data,
            function ($mail) use ($data, $information) {
                $mail->from($information['from'])
                    ->to($information['to']);
            }
        );

        return;
    }

    /**
     * @param $template
     * @param $information
     * @param $data
     */
    public static function sendCreditMail($mailData)
    {
        Mail::send(
            $mailData['template'],
            $mailData,
            function ($mail) use ($mailData) {
                $mail->from($mailData['from'], $mailData['name'])
                    ->subject($mailData['subject'])
                    ->to($mailData['to']);
            }
        );

        return;
    }

    /**
     * @return array
     */
    public static function failures()
    {
        return Mail::failures();
    }

    /**
     * @param $from
     * @param $to
     * @param $subject
     * @param $body
     * @param $headers
     */
    public static function sendMailWithHeader($from, $to, $subject, $body, $headers)
    {
        Mail::raw(
            $body,
            function ($message) use ($from, $to, $subject, $headers) {
                $message->from($from);
                $message->to($to);
                $message->subject($subject);
                $message->getSwiftMessage();
                foreach ($headers as $key => $val) {
                    $head = $message->getHeaders()->get($key);
                    $head->setValue($val);
                }
            }
        );
    }

    /**
     * @param string $template
     * @param mixed $data
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string|null $bcc
     * @param array $headers
     */
    public static function sendMailTemplate($template, $data, $from, $to, $subject, $bcc = null, $headers = [])
    {
        Mail::send(
            $template,
            $data,
            function ($message) use ($from, $to, $bcc, $headers, $subject) {
                $message->from($from);
                $message->to($to);
                $message->subject($subject);
                if ($bcc != null && $bcc != "") {
                    $message->bcc($bcc);
                }
                foreach ($headers as $key => $value) {
                    $message->getHeaders()->addTextHeader($key, $value);
                }
            }
        );
    }

    /**
     * @param array|string $toData
     * @param array|string $mailObject
     * @param $bcc
     * @return mixed
     */
    public static function sendMailWithBCC($toData, $mailObject, $bcc)
    {
        return Mail::to($toData)
            ->bcc($bcc)
            ->send($mailObject);
    }
}
