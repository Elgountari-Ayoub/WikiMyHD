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
        return response()->json([
            'users' => User::latest()->get()
        ], 200);
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
        $this->setStatus($id, 0);
    }

    public function setStatus(Request $request)
    {
        try {
            $request->validate(
                [
                    'id_user' => 'required',
                    'status' => 'required',
                ]
            );

            User::whereIn('id', [$request->id_user])
            ->update([
                'status' => $request->status,
            ]);
            
            $user = User::find($request->id_user);
            // Return a success response
            return response()->json([
                'message' => 'User status updated successfully',
                'user' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'error in updating user status',
                'user' => $user
            ], 404);
        }
    }
}
