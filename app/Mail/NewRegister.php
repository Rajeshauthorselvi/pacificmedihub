<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRegister extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $email, $password)
    {
        $this->first_name = $first_name;
        $this->email      = $email;
        $this->password   = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@authorselvi.com')->view('admin.emails.newAccount',['first_name'=>$this->first_name,'email'=>$this->email,'password'=>$this->password])->subject('Pacific Medihub - New Accout Register | '.date('d-m-Y'));
    }
}
