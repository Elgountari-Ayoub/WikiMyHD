<?php

namespace App\Http\Controllers;

use App\Mail\MembershipApplicationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MembershipApplicationMailController extends Controller
{
    public $userName;
    public $userEmail;
    public $userPoste;

    public $logoUrl;
    public $demandesUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $email, $poste)
    {
        $this->userName = $name;
        $this->userEmail = $email;
        $this->userPoste = $poste;
        $this->demandesUrl = env('FRONTEND_URL').'/users';

    }

    public function sendMail()
    {
       
        Mail::to('elgountariayoub22@gmail.com')->send(new MembershipApplicationMail(
            $this->userName,
            $this->userEmail,
            $this->userPoste,
            $this->demandesUrl
        ));
    }
}
