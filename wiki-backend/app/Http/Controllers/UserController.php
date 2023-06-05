<?php

namespace App\Http\Controllers;

use App\Models\Manual;
use App\Models\Space;
use Exception;
use App\Models\User;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // GET THE USERS AND HIS SPACES, MANUALS DATA
            $users = User::with('spaces', 'manuals')->get();
            return response()->json([
                'users' => $users
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'users' => null,
                'message' => $e->getMessage()
            ], 402);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // GET THE USERS AND HIS SPACES, MANUALS DATA
            $user = User::with('spaces', 'manuals')->findOrFail($id);
            return response()->json([
                'user' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'user' => null,
                'message' => $e->getMessage()
            ], 402);
        }
    }

    /**
     * DISPLAY THE AUTH USER
     */
    public function getAuth()
    {
        try {
            $id = Auth::id();
            // GET THE USERS AND HIS SPACES, MANUALS DATA
            $user = User::with('spaces', 'manuals')->findOrFail($id);
            // $spaces = $user->spaces()->with('users', 'manuals')->latest();
            // $user->spaces = $user->spaces()->with('users', 'manuals')->latest();
            // $user->manuals = $user->manuals()->with('users', 'space')->latest();


            return response()->json([
                'user' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'user' => null,
                'message' => $e->getMessage()
            ], 402);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $user = $request->user();

            // Validate the request data
            $validatedData = [];

            if ($request->filled('name')) {
                $validatedData['name'] = $request->input('name');
            }

            if ($request->filled('email')) {
                $validatedData['email'] = $request->input('email');
            }

            if ($request->filled('password')) {
                $validatedData['password'] = Hash::make($request->input('password'));
            }

            //Handle photo upload
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoPath = $photo->store('images/users', 'public');
                $validatedData['photo'] = $photoPath ?? null;
            }

            if ($request->filled('role')) {
                $validatedData['role'] = $request->input('role');
            }


            if ($request->filled('post')) {
                $validatedData['post'] = $request->input('post');
            }

            $validator = Validator::make($validatedData, [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
                'password' => 'sometimes|required|min:8',
                'role' => 'sometimes|required|string|max:255',
                'post' => 'sometimes|required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Update the user's properties
            $user->update($validatedData);

            // Return a success response
            return response()->json([
                'message' => 'User information updated successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            // Return a generic error response
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->updateStatus($id, 0);
    }

    /**
     * Update the specified resource from storage
     */
    public function updateStatus(Request $request)
    {
        try {
            $request->validate(
                [
                    'user_id' => 'required',
                    'status' => 'required',
                ]
            );

            User::whereIn('id', [$request->user_id])
                ->update([
                    'status' => $request->status,
                ]);

            $user = User::find($request->user_id);
            $spaces = $user->spaces()->with('manuals')->get();

            // $spaces = $user->manuals()->where('space_id', $spaceId)->with('users', 'space')->get();
            // $spaces = $user->spaces->where('user_id', $user->id)->with('users', 'space')->get();


            $spaces = $user->spaces()->with(['manuals' => function ($query) use ($user) {
                $query->whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                });
            }])->get();




            Log::info("$spaces");

            // send a register confirmed mail
            $mail = new RegisterConfirmedMailController($user->name, $spaces);
            $mail->sendMail();
            // Return a success response
            return response()->json([
                'message' => 'User status updated successfully',
                'user' => $user,
                'spaces' => $spaces,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'user' => null
            ], 404);
        }
    }


    public function getAuthStatus()
    {
        $user = Auth::user() ?? null;
        return response()->json([
            'res' => $user === null ? false : true
        ]);
    }

    public function store(Request $request)
    {
        try {
            // VALIDATE REQUEST
            $request->validate([
                'title' => 'required',
                'description' => 'required'
            ]);
            $space = new Space();
            $space->title = $request->title;
            $space->description = $request->description;
            $space->save();


            // ADD SPACE USER
            $creator_id = Auth::id();
            $pivotData = [
                'is_creator' => true, //tHE REQUEST PROTECTED BY THE IS_ADMIN MIDDLEWARE !!
            ];
            $space->users()->syncWithoutDetaching([$creator_id => $pivotData]);

            // GET THE SPACE WITH HIS USERS, MANUAL DATA
            $space = Space::with('users', 'manuals')->find($space->id);
            return response()->json([
                'space' => $space
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'space' => null,
                'message' => $e->getMessage()
            ], 402);
        }
    }

    public function assignSpace(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'spaces' => 'required|array'
            ]);

            $user = User::findOrFail($request->user_id);

            $spaces = Space::whereIn('id', $request->spaces)->get();

            $user->spaces()->syncWithoutDetaching($spaces->pluck('id')->toArray());

            return response()->json([
                'user' => $user,
                'space assigned' => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'space assigned' => false,
                'message' => $e->getMessage()
            ], 402);
        }
    }
    public function assignManual(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'manuals' => 'required|array'
            ]);

            $user = User::findOrFail($request->user_id);

            $manuals = Manual::whereIn('id', $request->manuals)->get();

            $user->manuals()->syncWithoutDetaching($manuals->pluck('id')->toArray());

            return response()->json([
                'user' => $user,
                'manual assigned' => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'manual assigned' => false,
                'message' => $e->getMessage()
            ], 402);
        }
    }
}
