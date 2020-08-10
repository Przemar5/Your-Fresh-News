<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;


    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        // $this->user = $user;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->data['from'])
            ->to('primero.el.dev@gmail.com')
            // ->subject()
            ->view('emails.contact');

        // return $this->from($this->data['from'], $this->user->name)
        //     ->to($this->user->email)
        //     ->subject($this->data['subject'])
        //     ->message($this->data['message'])
        //     ->view('emails.contact');
    }
}
