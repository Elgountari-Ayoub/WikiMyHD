<?php

namespace App\Http\Controllers;

use App\Models\Manual;
use App\Models\Space;
use Exception;
use App\Models\User;
// use Doctrine\Inflector\Rules\English\Rules;
use GuzzleHttp\Psr7\Message;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // GET THE USERS AND HIS SPACES, MANUALS DATA
            $users = User::with('spaces', 'manuals', 'articles')->get();
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

    public function getAuthStatus()
    {
        $user = Auth::user() ?? null;
        return response()->json([
            'res' => $user === null ? false : true,
            // 'user_status' => $user->status
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // GET THE USERS AND HIS SPACES, MANUALS DATA
            $user = User::with('spaces', 'manuals', 'articles')->findOrFail($id);
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
            $user = User::with('spaces', 'manuals', 'articles')->findOrFail($id);

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



    public function sendRegisterConfirmedMail(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            $spaces = $user->spaces()->with('manuals')->get();

            $spaces = $user->spaces()->with(['manuals' => function ($query) use ($user) {
                $query->whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                });
            }])->get();

            // send a register confirmed mail
            $mail = new RegisterConfirmedMailController($user->name, $user->email, $request->pass, $user->post, $spaces);
            $mail->sendMail();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function sendNewAccountCretaedMail(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            $spaces = $user->spaces()->with('manuals')->get();

            $spaces = $user->spaces()->with(['manuals' => function ($query) use ($user) {
                $query->whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                });
            }])->get();

            // send a register confirmed mail
            $mail = new NewAccountCreatedMailController($user->name, $user->email, $request->pass, $user->post, $spaces);
            $mail->sendMail();
        } catch (\Throwable $th) {
            //throw $th;
        }
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

            // Return a success response
            return response()->json([
                'message' => 'User status updated successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function approve(Request  $request)
    {
        try {
            $this->updateStatus($request);
            $this->sendRegisterConfirmedMail($request);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function archive(Request  $request)
    {
        try {
            $this->updateStatus($request); // to -1 if has been approved before, to -2 if hasn't
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Delete the user permanently
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'user' => $user,
                'message' => 'The user deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'user' => $id,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    public function store(Request $request)
    {
        // return response()->json(['message' => 'Registration successful', 'user' => $request]);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'post' => 'required|string'
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'post' => $request->input('post'),
        ]);

        // Assign spaces to him
        $assignSpacesRequest = new Request();
        $assignSpacesRequest = new Request([
            'user_id' => $user->id,
            'spaces' => $request->spaces,
        ]);
        $this->assignSpace($assignSpacesRequest);

        // Assign manuals to him
        $assignManualsRequest = new Request();
        $assignManualsRequest = new Request([
            'user_id' => $user->id,
            'manuals' => $request->manuals,
        ]);
        $this->assignManual($assignManualsRequest);

        // Approvement Request
        $approvementRequest = new Request();
        $approvementRequest = new Request([
            'user_id' => $user->id,
            'status' => 1,
        ]);
        $this->updateStatus($approvementRequest);

        // Aend new account created mail request
        $sendNewAccountCretaedMailRequest = new Request();
        $sendNewAccountCretaedMailRequest = new Request([
            'user_id' => $user->id,
            'pass' => $request->password,
        ]);
        $this->sendNewAccountCretaedMail($sendNewAccountCretaedMailRequest);

        // $mail = new MembershipApplicationMailController($user->name, $user->email, $user->post);
        // $mail->sendMail();

        event(new Registered($user));

        return response()->json(['message' => 'Registration successful', 'user' => $user]);
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
