<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BlockMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inputs)
    {
        //
        $this->inputs = $inputs;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /*return $this->from('ojaaksmtp@gmail.com')
                    ->subject('User Blocked')
                    ->view('mail.block',["data"=>$this->inputs]);*/
        return $this->subject('User Blocked')->view('mail.block',["data"=>$this->inputs]);
    }
}