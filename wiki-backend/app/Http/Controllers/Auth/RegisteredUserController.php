<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // 'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'post' => 'required|string'
        ]);

        // Handle photo upload
        // if ($request->hasFile('photo')) {
        //     $photo = $request->file('photo');
        //     $photoPath = $photo->store('photos', 'public');
        // }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'photo' => $photoPath ?? null,
            'post' => $request->input('post'),
        ]);

        event(new Registered($user));

        // Auth::login($user);

        return response()->json(['message' => 'Registration successful', 'user' => $user]);
        // return response()->noContent();
    }
}
