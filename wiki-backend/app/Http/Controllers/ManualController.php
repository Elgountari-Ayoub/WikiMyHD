<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Space;
use App\Models\Manual;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ManualController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        try {
            $authRole = Auth::user()->role;

            if ($authRole == 'admin') {
                $manuals =  Manual::with('users', 'space')->orderBy('id', 'desc')->get();
                return response()->json([
                    'manuals' => $manuals
                ], 200);
            } else {
                $user = User::findOrFail(Auth::id());
                $manuals = $user->manuals()->with('users', 'space')->latest()->get();
                return response()->json([
                    'manuals' => $manuals,
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'spaces' => null,
                'message' => $e->getMessage()
            ], 404);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // VALIDATE REQUEST
            $request->validate([
                'space_id' => 'required',
                'title' => 'required',
                'description' => 'required'
            ]);
            $manual = new Manual();
            $manual->space_id = $request->space_id;
            $manual->title = $request->title;
            $manual->description = $request->description;
            $manual->save();

            $creator_id = Auth::id();
            $pivotData = [
                'is_creator' => true,
            ];
            $manual->users()->syncWithoutDetaching([$creator_id => $pivotData]);

            // If the auth not an Admin Assign to him
            $authRole = Auth::user()->role;

            if ($authRole != 'admin') {
                $adminUser = User::where('role', 'admin')->first();
                $admin_id = $adminUser->id;
                $pivotData = [
                    'is_creator' => false,
                ];
                $manual->users()->syncWithoutDetaching([$admin_id => $pivotData]);
            }


            $manual = Manual::with('users', 'space')->findOrFail($manual->id);

            return response()->json([
                'manual' => $manual
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'manual' => null,
                'message' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     */

    public function show($id)
    {
        try {
            if (Auth::user()->role == 'admin') {
                // GET THE SPACE AND HIS USERS, MANUALS DATA
                $manual = Manual::with('users', 'space', 'articles')->findOrFail($id);

                return response()->json([
                    'manual' => $manual
                ], 200);
            } else {
                $user = User::findOrFail(Auth::id());
                $manual = $user->manuals()->with('users', 'space', 'articles')->findOrFail($id);

                return response()->json([
                    'manual' => $manual
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'manual' => null,
                'message' => $e->getMessage()
            ], 402);
        }
    }

    public function getManualsBySpaceId($spaceId)
    {
        try {
            if (Auth::user()->role == 'admin') {
                $manuals = Manual::with('users', 'space')->where('space_id', $spaceId)->get();

                return response()->json([
                    'manuals' => $manuals
                ], 200);
            } else {
                $user = User::findOrFail(Auth::id());
                $manuals = $user->manuals()->where('space_id', $spaceId)->with('users', 'space')->get();

                return response()->json([
                    'manuals' => $manuals
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'manual' => null,
                'message' => $e->getMessage()
            ], 402);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // VALIDATE REQUEST
            $request->validate([
                'title' => 'required',
                'description' => 'required'
            ]);

            $manual = Manual::findOrFail($id);
            $manual->title = $request->title;
            $manual->description = $request->description;
            $manual->save();

            $manual = Manual::with('users', 'space')->find($manual->id);
            return response()->json([
                'manual' => $manual
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'manual' => null,
                'message' => $e->getMessage()
            ], 402);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            // DELETE THE SPACE
            $manual = Manual::with('users', 'space')->findOrFail($id);
            $manual->delete();

            // GET THE SPACE AND HIS USERS, MANUALS DATA
            return response()->json([
                'manual' => $manual
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'manual' => null,
                'message' => $e->getMessage()
            ], 402);
        }
    }

    public function assignUserToManual(Request $request)
    {
        try {
            $request->validate([
                'manual_id' => 'required',
                'users' => 'required|array'
            ]);

            $manual = Manual::findOrFail($request->manual_id);

            $users = User::whereIn('id', $request->users)->get();

            $manual->users()->syncWithoutDetaching($users->pluck('id')->toArray());

            $space = $manual->space;

            $space->users()->syncWithoutDetaching($users->pluck('id')->toArray());


            return response()->json([
                'manual' => $manual,
                'users_assigned' => true
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'users_assigned' => false,
                'message' => $e->getMessage()
            ], 402);
        }
    }
    /**
     * Search for a name
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($title)
    {
        try {
            if (Auth::user()->role == 'admin') {
                $manuals = Manual::where('title', 'like', '%' . $title . '%')->with('users', 'space')->get();

                return response()->json([
                    'manuals' => $manuals
                ], 200);
            } else {
                $user = User::findOrFail(Auth::id());
                $manuals = $user->manuals()->where('title', 'like', '%' . $title . '%')->with('users', 'space')->get();

                return response()->json([
                    'manuals' => $manuals ?? null
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'manuals' => null,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
