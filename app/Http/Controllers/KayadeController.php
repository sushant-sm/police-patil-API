<?php

namespace App\Http\Controllers;

use App\Kayade;
use App\Policestation;
use Illuminate\Http\Request;

class KayadeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $userRole = $loggedinuser->role;
        $psid = $loggedinuser->psid;
        $psname = Policestation::where('id', $psid)->get('psname');

        if ($userRole == 'admin') {
            $data = Kayade::get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else if ($userRole == 'ps') {
            $data = Kayade::where('psid', $psid)->get();
            return response()->json(["message" => "Success", "data" => $data, "psname" => $psname], 200);
        } else if ($userRole == 'pp') {
            $data = Kayade::where('ppid', $uid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["message" => "You are not authorized person.l̥"], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'file' => 'nullable|mimes:pdf',
        ]);

        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/kayade/' . time() . '.' . $extension;
            $file->move('uploads/kayade', $filename);
            $data['file'] = $filename;
        }

        $kayade = Kayade::create($data);

        return response()->json(["message" => "Success", "data" => $kayade], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Kayade  $kayade
     * @return \Illuminate\Http\Response
     */
    public function show(Kayade $kayade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kayade  $kayade
     * @return \Illuminate\Http\Response
     */
    public function edit(Kayade $kayade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kayade  $kayade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kayade $kayade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kayade  $kayade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kayade $kayade)
    {
        //
    }
}
