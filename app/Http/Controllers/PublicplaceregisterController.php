<?php

namespace App\Http\Controllers;

use App\Publicplaceregister;
use App\Policestation;
use Validator;
use Illuminate\Http\Request;

class PublicplaceregisterController extends Controller
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
                $my_query = Publicplaceregister::query();
                if ($fromdate != NULL and $todate != NULL) {
                    if (!empty($q['place'])) {
                        $my_query->where('place', $q['place']);
                    }
                    if (!empty($q['isissue'])) {
                        $my_query->where('isissue', $q['isissue']);
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
                $data = Publicplaceregister::get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'ps') {

            $psid = $loggedinuser->psid;
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            $q = $request->query();
            if ($q != NULL) {
                $my_query = Publicplaceregister::query();
                if ($fromdate != NULL and $todate != NULL) {
                    if (!empty($q['place'])) {
                        $my_query->where('place', $q['place']);
                    }
                    if (!empty($q['isissue'])) {
                        $my_query->where('isissue', $q['isissue']);
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
                $data = Publicplaceregister::where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'pp') {
            $data = Publicplaceregister::where('ppid', $uid)->get();
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
            'place' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'iscctv' => 'nullable',
            'isissue' => 'nullable',
            'issuereason' => 'nullable',
            'issuecondition' => 'nullable',
            'crimeregistered' => 'nullable',
            'actionTaken' => 'nullable',
        ]);


        $loggedinuser = auth()->guard('api')->user();
        $ppid = $loggedinuser->id;
        $psid = $loggedinuser->psid;

        $data['ppid'] = $ppid;
        $data['psid'] = $psid;


        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/publicplaceregister/' . time() . '.' . $extension;
            $file->move('uploads/publicplaceregister', $filename);
            $data['photo'] = $filename;
        }

        $publicplace = Publicplaceregister::create($data);
        app('App\Http\Controllers\PointsController')->addpoint();
        return response()->json(["message" => "Success", "data" => $publicplace], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Publicplaceregister  $publicplaceregister
     * @return \Illuminate\Http\Response
     */
    public function show(Publicplaceregister $publicplaceregister, $id)
    {
        $publicplace = Publicplaceregister::find($id);
        if (is_null($publicplace)) {
            return response()->json(["message" => "Record Not found"], 404);
        }
        return response()->json(["message" => "Success", "data" => $publicplace], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Publicplaceregister  $publicplaceregister
     * @return \Illuminate\Http\Response
     */
    public function edit(Publicplaceregister $publicplaceregister)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Publicplaceregister  $publicplaceregister
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $publicplaceid = $request->publicplaceid;
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $getppid = Publicplaceregister::where('id', $publicplaceid)->pluck('ppid');
        $getppid = trim($getppid, '[]',);
        $getppid = (int)$getppid;

        $getpsid = Publicplaceregister::where('id', $publicplaceid)->pluck('ppid');
        $getpsid = trim($getpsid, '[]',);
        $getpsid = (int)$getpsid;

        if ($getppid == $uid or $getpsid == $uid) {
            $data = $request->validate([
                'place' => 'nullable|string',
                'address' => 'nullable|string',
                'latitude' => 'nullable',
                'longitude' => 'nullable',
                'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
                'iscctv' => 'nullable',
                'isissue' => 'nullable',
                'issuereason' => 'nullable',
                'issuecondition' => 'nullable',
                'crimeregistered' => 'nullable',
                'actionTaken' => 'nullable',
            ]);

            if ($request->hasfile('photo')) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                $filename = 'pp.thesupernest.com/uploads/publicplaceregister/' . time() . '.' . $extension;
                $file->move('uploads/publicplaceregister', $filename);
                $data['photo'] = $filename;
            }

            $publicplace = Publicplaceregister::where('id', $publicplaceid)->update($data);
            return response()->json(["message" => "Success", "data" => $publicplace], 200);
        } else {
            return response()->json(["message" => "You are not authorized person."], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Publicplaceregister  $publicplaceregister
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $publicplaceid = $request->publicplaceid;
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $getppid = Publicplaceregister::where('id', $publicplaceid)->pluck('ppid');
        $getppid = trim($getppid, '[]',);
        $getppid = (int)$getppid;

        $getpsid = Publicplaceregister::where('id', $publicplaceid)->pluck('ppid');
        $getpsid = trim($getpsid, '[]',);
        $getpsid = (int)$getpsid;

        if ($getppid == $uid or $getpsid == $uid) {
            $publicplace = Publicplaceregister::find($publicplaceid);
            $publicplace->delete();
            return response()->json(["message" => "Success"], 200);
        } else {
            return response()->json(["message" => "You are not authorized person."], 404);
        }
    }

    public function showbyppid($ppid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($ppid == $uid) {
            $data = Publicplaceregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }
    }

    public function showbypsid($psid)
    {
        $data = Publicplaceregister::orderBy('id', 'desc')->where('psid', $psid)->get();
        return response()->json(["message" => "Success", "data" => $data], 200);
    }
}
