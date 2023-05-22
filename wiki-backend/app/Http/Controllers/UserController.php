<?php

namespace App\Http\Controllers;

use App\Models\User;
// use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Response as HttpResponse;


class UserController extends Controller
{
    public function setStatus($userId, $value = 'approved'): Response
    {
        $user = User::find($userId);

        if ($user) {
            $user->column_name = $value;
            $user->save();
            
        } else {
        }
        return response()->noContent();
    }
}
