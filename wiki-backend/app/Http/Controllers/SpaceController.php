<?php

namespace App\Http\Controllers;

use App\Models\Space;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

use function PHPUnit\Framework\isEmpty;

class SpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $authRole = Auth::user()->role;

            if ($authRole == 'admin') {
                $spaces =  Space::with('users', 'manuals')->orderBy('id', 'desc')->get();
                return response()->json([
                    'spaces' => $spaces,
                ], 200);
            } else {
                $user = User::findOrFail(Auth::id());

                $spaces = $user->spaces()->with(['users','manuals' => function ($query) use ($user) {
                    $query->whereHas('users', function ($query) use ($user) {
                        $query->where('users.id', $user->id);
                    });
                }])->get();

                return response()->json([
                    'spaces' => $spaces,
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
                'is_creator' => true,
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

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            if (Auth::user()->role == 'admin') {
                // GET THE SPACE AND HIS USERS, MANUALS DATA
                $space = Space::with('users', 'manuals')->findOrFail($id);

                return response()->json([
                    'space' => $space
                ], 200);
            } else {
                // GET THE SPACE AND HIS USERS, MANUALS DATA
                $user = User::findOrFail(Auth::id());
                // $space = $user->spaces()->with('users', 'manuals')->findOrFail($id);
                $space = $user->spaces()->with(['users','manuals' => function ($query) use ($user) {
                    $query->whereHas('users', function ($query) use ($user) {
                        $query->where('users.id', $user->id);
                    });
                }])->findOrFail($id);

                return response()->json([
                    'space' => $space
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'space' => null,
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

            // UPDATE THE SPACE DATA OF THE AUTH USER 
            $space = Space::findOrFail($id);
            $space->title = $request->title;
            $space->description = $request->description;
            $space->save();

            // GET THE SPACE AND HIS USERS, MANUALS DATA
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            // DELETE THE SPACE
            $space = Space::with('users', 'manuals')->findOrFail($id);
            $space->delete();

            // GET THE SPACE AND HIS USERS, MANUALS DATA
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

    /**
     * Search for a name
     *
     */
    public function search($title)
    {
        try {
            if (Auth::user()->role == 'admin') {
                // GET THE SPACE AND HIS USERS, MANUALS DATA
                $spaces = Space::where('title', 'like', '%' . $title . '%')->with('users', 'manuals')->get();

                return response()->json([
                    'spaces' => $spaces
                ], 200);
            } else {
                // GET THE SPACE AND HIS USERS, MANUALS DATA
                $user = User::findOrFail(Auth::id());
                $spaces = $user->spaces()->where('title', 'like', '%' . $title . '%')->with('users', 'manuals')->get();

                return response()->json([
                    'spaces' => $spaces ?? null
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'spaces' => null,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
