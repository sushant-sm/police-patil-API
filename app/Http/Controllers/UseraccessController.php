<?php

namespace App\Http\Controllers;

use App\Useraccess;
use Illuminate\Http\Request;
use Validator;

class UseraccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $useraccess = Useraccess::get();
        return response()->json(["message" => "success", "data" => $useraccess], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return response()->json(["messege"=>"sadf"]);

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
            'user_id' => 'required',
            'tables' => 'required',
        ]);
        $data['tables'] = json_encode($data['tables']);
        // return $data;

        $useraccess = Useraccess::create($data);
        return response()->json(["message" => "Success", "data" => $useraccess], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Useraccess  $useraccess
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $data = Useraccess::where('user_id', $user_id)->get(["tables"]);
        $a = json_decode($data[0]['tables']);
        return $a;
        return response()->json(["message" => "success", "data" => $data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Useraccess  $useraccess
     * @return \Illuminate\Http\Response
     */
    public function edit(Useraccess $useraccess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Useraccess  $useraccess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Useraccess $useraccess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Useraccess  $useraccess
     * @return \Illuminate\Http\Response
     */
    public function destroy(Useraccess $useraccess)
    {
        //
    }

    public function useraccesstable(Useraccess $useraccess, $user_id)
    {
        $data = Useraccess::orderBy('id', 'desc')->where('user_id', $user_id)->get(["tables"]);
        return response()->json($data);
    }
}
