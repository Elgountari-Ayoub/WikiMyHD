<?php

namespace App\Http\Controllers;

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
        return Manual::latest()->get();
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
        //
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
}
