<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailUpdate extends Mailable
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
       /* return $this->view('view.name');*/
       return $this->subject('Email Verification')->view('mail.updatemail',["data"=>$this->inputs]);
       /*return $this->subject('Contacts Information')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email Address', $inputs['path'])
            ->line('If you did not create an account, no further action is required.');*/
            //->view('mail.updatemail',["data"=>$this->inputs]);
    }
}
