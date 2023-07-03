<?php

namespace App\Http\Controllers;

use App\Mail\RegisterConfirmedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RegisterConfirmedMailController extends Controller
{
    public $userName;
    public $email;
    public $pass;
    public $post;
    public $spaces;
    public $loginUrl;
    public $logoUrl;



    public function __construct($userName, $email, $pass, $post, $spaces)
    {
        $this->userName = $userName;
        $this->email = $email;
        $this->pass = $pass;
        $this->post = $post;
        $this->spaces = $spaces;
    }

    public function sendMail()
    {
        $this->loginUrl = env('FRONTEND_URL') . '/login';
        $this->logoUrl = env('APP_LOGO');

        Mail::to($this->email)->send(new RegisterConfirmedMail(
            $this->userName,
            $this->email,
            $this->pass,
            $this->post,
            $this->spaces,
            $this->loginUrl,
            $this->logoUrl
        ));
    }
}
