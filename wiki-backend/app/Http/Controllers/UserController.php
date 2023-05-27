<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'user' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'something went wrong',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request)
    // {
    //     try {
    //         $user = $request->user();
    //         // Validate the request data

    //         $validatedData = [];

    //         if ($request->filled('name')) {
    //             $validatedData['name'] = $request->validate([
    //                 'name' => 'required|string|max:255',
    //             ]);
    //         }
    //         if ($request->filled('email')) {
    //             $validatedData['email'] = $request->validate([
    //                 'email' => 'required|string|max:255',
    //             ]);
    //         }
    //         if ($request->filled('name')) {
    //             $validatedData['name'] = $request->validate([
    //                 'name' => 'required|string|max:255',
    //             ]);
    //         }

    //         // Update the user's name
    //         $user->update(['name' => $request->input('name')]);
    //         $user->name = $request->input('name');
    //         $user->save();

    //         // Return a success response
    //         return response()->json(['message' => 'Name updated successfully'], 200);
    //     } catch (ValidationException $e) {
    //         // Return a validation error response
    //         return response()->json(['errors' => $e->errors()], 422);
    //     } catch (\Exception $e) {
    //         // Return a generic error response
    //         return response()->json(['message' => 'An error occurred'], 500);
    //     }
    // }

    public function update(Request $request)
    {
        // if ($request->hasFile('photo')) {
        //     return 1;
        //     $photo = $request->file('photo');
        //     $photoPath = $photo->store('photos', 'public');
        // }
        // return 0;
        try {
            // // Return a success response
            // return response()->json([
            //     'requestt' =>  $request->input('name')
            //     // 'requestt' => $request->file('photo')
            // ], 200);
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

            // if ($request->hasFile('photo')) {
            //     return 1;
            //     $photo = $request->file('photo');
            //     $photoPath = $photo->store('photos', 'public');
            // }

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

    // public function update(Request $request)
    // {
    //     // return response()->json([
    //     //     'message' => 'Hello',
    //     //     'request' => $request
    //     // ], 200);
    //     try {
    //         $user = $request->user();

    //         // Validate the request data
    //         $validatedData = [];

    //         if ($request->filled('name')) {
    //             $validatedData['name'] = $request->input('name');
    //         }

    //         if ($request->filled('email')) {
    //             $validatedData['email'] = $request->input('email');
    //         }

    //         if ($request->filled('password')) {
    //             $validatedData['password'] = Hash::make($request->input('password'));
    //         }

    //         // Handle photo upload
    //         if ($request->hasFile('photo')) {
    //             $photo = $request->file('photo');
    //             $photoPath = $photo->store('photos', 'public');
    //             $validatedData['photo'] = $photoPath ?? null;
    //         }

    //         $validator = Validator::make($validatedData, [
    //             'name' => 'sometimes|required|string|max:255',
    //             'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
    //             'password' => 'sometimes|required|min:8',
    //             'photo' => 'sometimes|required|image|max:2048',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json(['errors' => $validator->errors()], 422);
    //         }

    //         // Update the user's properties
    //         $user->update($validatedData);

    //         // Return a success response
    //         return response()->json([
    //             'message' => 'User information updated successfully',
    //             'user' => $user
    //         ], 200);
    //     } catch (\Exception $e) {
    //         // Return a generic error response
    //         return response()->json(['message' => 'An error occurred'], 500);
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function setStatus($userId, $value = 0): Response
    {
        $user = User::find($userId);

        if ($user) {
            $user->status = $value;
            $user->save();
        } else {
        }
        return response()->noContent();
    }
}
