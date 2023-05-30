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



    public function sendMail()
    {
        $userName = 'ayoub';
        $spaces = [
            [
                'name' => 'IT'
            ],
            [
                'name' => 'Marketing'
            ],
            [
                'name' => 'Design'
            ],
        ];
        $loginUrl = env('FRONTEND_URL').'/login';
        $logoUrl = env('APP_LOGO');

        Mail::to('elgountariayoub22@gmail.com')->send(new RegisterConfirmedMail(
            $userName = $userName,
            $spaces = $spaces,
            $loginUrl = $loginUrl,
            $logoUrl = $logoUrl
        ));
    }
}
