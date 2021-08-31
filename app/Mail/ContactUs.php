<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable
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
        //echo "<pre>";print_r($this->inputs);die;
        if($this->inputs['document'] == ''){

            return $this->subject('Enquiry')
                    ->view('mail.contactform',["data"=>$this->inputs]);
        }else{

            return $this->subject('Enquiry')
                    ->attach($this->inputs['document'])
                    ->view('mail.contactform',["data"=>$this->inputs]);
        }
        
    }
}
