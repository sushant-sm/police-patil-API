<?php

namespace App\Http\Controllers;

use App\Movementregister;
use App\Policestation;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Validator;

class MovementregisterController extends Controller
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

            $q = $request->query();
            if ($q != NULL) {
                $fromdate = $request->fromdate;
                $todate = $request->todate;
                $my_query = Movementregister::query();
                if ($fromdate != NULL and $todate != NULL) {
                    if (!empty($q['type'])) {
                        $my_query->where('type', $q['type']);
                    }
                    if (!empty($q['essue'])) {
                        $my_query->where('essue', $q['essue']);
                    }
                    if (!empty($q['psid'])) {
                        $my_query->where('psid', $q['psid']);
                    }
                    if (!empty($q['ppid'])) {
                        $my_query->where('ppid', $q['ppid']);
                    }
                    $my_query->whereBetween('created_at', [$fromdate, $todate]);
                } else {
                    foreach ($request->query() as $key => $value) {
                        $my_query->where($key, $value);
                    }
                }
                $data = $my_query->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Movementregister::get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'ps') {

            $psid = $loggedinuser->psid;
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            $q = $request->query();
            if ($q != NULL) {
                $my_query = Movementregister::query();
                if ($fromdate != NULL and $todate != NULL) {
                    if (!empty($q['type'])) {
                        $my_query->where('type', $q['type']);
                    }
                    if (!empty($q['issue'])) {
                        $my_query->where('issue', $q['issue']);
                    }
                    if (!empty($psid)) {
                        $my_query->where('psid', $psid);
                    }
                    if (!empty($q['ppid'])) {
                        $my_query->where('ppid', $q['ppid']);
                    }
                    $my_query->whereBetween('created_at', [$fromdate, $todate]);
                } else {
                    foreach ($request->query() as $key => $value) {
                        $my_query->where($key, $value);
                    }
                    $my_query->where('psid', $psid);
                }
                $data = $my_query->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Movementregister::where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'pp') {
            $data = Movementregister::where('ppid', $uid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["message" => "You are not authorized person.l̥"], 200);
        }
    }

    public function latest()
    {
        $data = Movementregister::latest()->take(10)->get();
        return response()->json(["message" => "Sucees", "data" => $data], 200);
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
            'type' => 'required|string',
            'subtype' => 'required|string',
            'address' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'movement_type' => 'nullable',
            'leader' => 'nullable',
            'datetime' => 'nullable',
            'issue' => 'nullable|boolean',
            'attendance' => 'nullable|integer',
            'description' => 'nullable',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
        ]);

        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $psid = $loggedinuser->psid;

        $data['ppid'] = $ppid;
        $data['psid'] = $psid;

        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/movementregister/' . time() . '.' . $extension;
            $file->move('uploads/movementregister', $filename);
            $data['photo'] = $filename;
        }

        $movement = Movementregister::create($data);
        app('App\Http\Controllers\PointsController')->addpoint();
        return response()->json(["message" => "Success", "data" => $movement], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movementregister  $movementregister
     * @return \Illuminate\Http\Response
     */
    public function show(Movementregister $movementregister, $id)
    {
        $movement = Movementregister::find($id);
        if (is_null($movement)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $movement], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Movementregister  $movementregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Movementregister $movementregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movementregister  $movementregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movementregister $movementregister)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movementregister  $movementregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movementregister $movementregister)
    {
        //
    }

    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Movementregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }
    }

    public function showbypsid($psid)
    {
        $data = Movementregister::orderBy('id', 'desc')->where('psid', $psid)->get();
        return response()->json(["message" => "Success", "data" => $data], 200);
    }
}
