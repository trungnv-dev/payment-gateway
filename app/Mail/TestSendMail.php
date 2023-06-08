<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestSendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data; // using public data then don't need with
    // protected $data; // using public data then need with
    public $subject;
    public $view;

    public function __construct($data, $subject, $view)
    {
        $this->data = $data;
        $this->subject = $subject;
        $this->view = $view;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = config('app.mail_sender');
        $name = config('app.mail_sender_name');

        return $this->view($this->view)
                // ->text($this->view)
                ->from($address, $name)
                ->subject($this->subject);
                // ->with(['data' => $this->data]);
    }
}
