<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Space;
use App\Models\Manual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                return response()->json([
                    'manuals' => Manual::latest()->get()
                ], 200);
            } else {
                $manuals = Manual::where('id_user', Auth::user()->id)->latest()->get();
                return response()->json([
                    'manuals' => $manuals,
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'FAIL TO GET THE MANUALS',
            ], 404);
        }
    }
    public function manualsBySpace($space_id)
    {
        try {
            $space = Space::find($space_id);
            $manuals = $space->manuals()->get();
            foreach ($manuals as $manual) {
                $manual['space'] = $space;
            }
            return response()->json([
                'manuals' => $manuals,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'FAIL TO GET THE MANUALS BY THE SPACE',
            ], 404);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $userId = Auth::id();
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'id_space' => 'required'
            ]);
            $request['id_user'] = $userId;
            // return response()->json([
            //     'message' => 'u have pass the validation',
            //     'req' => $request->all()
            // ]);

            $manual = new Manual();

            return Manual::create($request->all());
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => 'Bro, w9a3 chi blan f l\'insertion dyal manual',
                'your fucking rquest' => $request
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Manual $manual)
    {
        return Manual::find($manual);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $space = Manual::find($id);
        $space->update($request->all());
        return $space;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            return Manual::destroy($id);
        } catch (\Throwable $th) {
            //throw $th;
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
        $manual = Manual::where('title', 'like', '%' . $title . '%')->get();

        return response()->json($manual ?? null);
    }
}
