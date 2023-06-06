<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $secret;

    /**
     * Create a new message instance.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct(string $secret)
    {
        $this->secret= $secret;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.shipped')->with([
            'secret' => $this->secret,
        ]);
;

    }
}
