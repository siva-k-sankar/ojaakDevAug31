<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RefundMail extends Mailable
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
        return $this->subject('Refunded')->view('mail.refundedamount',["data"=>$this->inputs]);
        //return $this->subject('Refunded')->view('mail.refundedamount',["data"=>$this->inputs]);

       /*return $this->subject('Contacts Information')
            ->line('We are processed refund of following payment,Amount will be credited to your account 6-7 days')
            //->action('Verify Email Address', $inputs['path'])
            ->line('Amount: '.$this->inputs['amount']);
            //->view('mail.refundedamount',["data"=>$this->inputs]);*/

        //return $this->view('view.name');
    }
}
