<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request) //: Response
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();
            $id = Auth::id();
            $user = User::findOrFail($id);
            if ($user->status === 1) {
                return response()->json([
                    'user' => $user,
                    'approved' => true
                ], 200);
            } else {
                Auth::logout($user);
                return response()->json([
                    'user' => $user,
                    'approved' => false
                ], 200);
            }
        } catch (Exception $e) {
            Auth::logout($user);
            return response()->json([
                'user' => null,
                'error message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
