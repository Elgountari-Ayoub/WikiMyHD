<?php

namespace App\Http\Controllers;

use App\Mail\ShareArticleMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class ShareArticleMailController extends Controller
{
    public $email;
    public $pdf;
    public function __construct($pdf, $email)
    {
        $this->pdf = $pdf;
    }

    public function sendMail()
    {



        // Mail::send('emails.myTestMail', $data, function ($message) use ($data, $pdf) {
        //     $message->to($data["email"], $data["email"])
        //         ->subject($data["title"])
        //         ->attachData($pdf->output(), "text.pdf");
        // });
        Mail::to($this->email)->send(
            new ShareArticleMail(
                $this->pdf,
            )
        );
    }
}