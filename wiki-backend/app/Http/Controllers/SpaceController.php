<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class SpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authRole = Auth::user()->role;
        if ($authRole == 'admin'){
            $spaces =  Space::latest()->get();
            return response()->json([
                'spaces' => $spaces,
            ], 200);
        }else {
            $spaces = Space::where('id_user', Auth::user()->id)->latest()->get();
            return response()->json([
                'spaces' => $spaces,
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);
        $request['id_user'] = 1;

        return Space::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Space::find($id);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $space = Space::find($id);
        $space->update($request->all());
        return $space;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            return Space::destroy($id);
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
        $spaces = Space::where('title', 'like', '%' . $title . '%')->get();

        // if (count($spaces) == 0) {
        //     $spaces = Space::latest()->get();
        // }
        return response()->json($spaces);
    }
}
