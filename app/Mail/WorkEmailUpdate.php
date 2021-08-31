<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WorkEmailUpdate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inputs)
    {
        $this->inputs = $inputs;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    { 

       return $this->subject('Work Email Verification')->view('mail.updatemail',["data"=>$this->inputs]);
    }
}
