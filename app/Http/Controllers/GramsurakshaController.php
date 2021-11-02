<?php

namespace App\Http\Controllers;

use App\Gramsuraksha;
use App\Policestation;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Http\Request;

class GramsurakshaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $userRole = $loggedinuser->role;
        $psid = $loggedinuser->psid;
        $psname = Policestation::where('id', $psid)->get('psname');

        if ($userRole == 'admin') {
            $data = Gramsuraksha::get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else if ($userRole == 'ps') {
            $data = Gramsuraksha::where('psid', $psid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else if ($userRole == 'pp') {
            $data = Gramsuraksha::where('ppid', $uid)->get();
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
            'name' => 'required|string',
            'skill' => 'required|string',
            'mobile' => 'required|numeric|digits:10',
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $psid = $loggedinuser->psid;

        $data['ppid'] = $ppid;
        $data['psid'] = $psid;

        $disaster = Gramsuraksha::create($data);

        return response()->json(["message" => "Success", "data" => $disaster], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Gramsuraksha  $gramsuraksha
     * @return \Illuminate\Http\Response
     */
    public function show(Gramsuraksha $gramsuraksha)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Gramsuraksha  $gramsuraksha
     * @return \Illuminate\Http\Response
     */
    public function edit(Gramsuraksha $gramsuraksha)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Gramsuraksha  $gramsuraksha
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gramsuraksha $gramsuraksha)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Gramsuraksha  $gramsuraksha
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gramsuraksha $gramsuraksha)
    {
        //
    }
}
