<?php

namespace App\Http\Controllers;

use App\Mail\RegisterConfirmedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegisterConfirmedMailController extends Controller
{
    public $userName;
    public $spaces;
    public $loginUrl;
    public $logoUrl;


    public function __construct($userName, $spaces)
    {
        $this->userName = $userName;
        $this->spaces = $spaces;
    }

    public function sendMail()
    {
        $this->loginUrl = env('FRONTEND_URL').'/login';
        $this->logoUrl = env('APP_LOGO');

        Mail::to('elgountariayoub22@gmail.com')->send(new RegisterConfirmedMail(
            $this->userName,
            $this->spaces,
            $this->loginUrl,
            $this->logoUrl
        ));
    }
}
