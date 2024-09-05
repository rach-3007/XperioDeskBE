<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bookingDetails;  // Booking details to be passed to the email view

    public function __construct($bookingDetails)
    {
        $this->bookingDetails = $bookingDetails;
    }

    public function build()
    {
        return $this->from('rachelrajan2001@gmail.com')
                    ->to('sethumanohar@gmail.com')
                    ->subject('Booking Confirmation')
                    ->view('emails.booking-confirmation')
                    ->with([
                        'details' => $this->bookingDetails,
                    ]);
    }
}
