<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Watchregister;
use App\Policestation;

class WatchregisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $q = $request->query();
        if ($q != NULL) {
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            $my_query = Watchregister::query();
            if ($fromdate != NULL and $todate != NULL) {
                if (!empty($q['type'])) {
                    $my_query->where('type', $q['type']);
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
            $data = Watchregister::get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        }
    }


    public function latest()
    {
        $data = Watchregister::latest()->take(10)->get();
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
        $loggedinuser = auth()->guard('api')->user();
        $role = $loggedinuser->role;
        // return $role;
        $data = $request->validate([
            'type' => 'required|string',
            'name' => 'required|string',
            'mobile' => 'nullable|numeric|digits:10',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'aadhar' => 'nullable|image|mimes:jpg,png,jpeg,svg,pdf',
            'address' => 'nullable',
            'tadipar_area' => 'nullable',
            'tadipar_date' => 'nullable',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'description' => 'nullable|string',
            'otherphoto' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'actionTaken' => 'nullable',
        ]);

        $uid = $loggedinuser->id;
        $psid = $loggedinuser->psid;
        $role = $loggedinuser->role;
        if ($role == 'pp') {
            $data['ppid'] = $uid;
            $data['psid'] = $psid;
        } else if ($role == 'ps' or $role == 'admin') {
            $data['psid'] = $request->psid;
            $data['ppid'] = $request->ppid;
        }

        if ($request->hasfile('aadhar')) {
            $file = $request->file('aadhar');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/watchregister/aadhar/' . time() . '.' . $extension;
            $file->move('uploads/watchregister/aadhar', $filename);
            $data['aadhar'] = $filename;
        }
        if ($request->hasfile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/watchregister/photo/' . time() . '.' . $extension;
            $file->move('uploads/watchregister/photo', $filename);
            $data['photo'] = $filename;
        }
        if ($request->hasfile('otherphoto')) {
            $file = $request->file('otherphoto');
            $extension = $file->getClientOriginalExtension();
            $filename = 'pp.thesupernest.com/uploads/watchregister/otherphoto/' . time() . '.' . $extension;
            $file->move('uploads/watchregister/otherphoto', $filename);
            $data['otherphoto'] = $filename;
        }

        $watch = Watchregister::create($data);
        app('App\Http\Controllers\PointsController')->addpoint();
        return response()->json(["message" => "Success", "data" => $watch], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $armsregister = Watchregister::find($id);
        if (is_null($armsregister)) {
            return response()->json(["error" => "Record Not found"], 404);
        }
        return response()->json($armsregister, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $watchid = $request->watchid;
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $getppid = Watchregister::where('id', $watchid)->pluck('ppid');
        $getppid = trim($getppid, '[]',);
        $getppid = (int)$getppid;

        $getpsid = Watchregister::where('id', $watchid)->pluck('ppid');
        $getpsid = trim($getpsid, '[]',);
        $getpsid = (int)$getpsid;

        if ($getppid == $uid or $getpsid == $uid) {
            $data = $request->validate([
                'type' => 'nullable|string',
                'name' => 'nullable|string',
                'mobile' => 'nullable|numeric|digits:10',
                'photo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
                'aadhar' => 'nullable|image|mimes:jpg,png,jpeg,svg,pdf',
                'address' => 'nullable',
                'tadipar_area' => 'nullable',
                'tadipar_date' => 'nullable',
                'latitude' => 'nullable',
                'longitude' => 'nullable',
                'description' => 'nullable|string',
                'otherphoto' => 'nullable|image|mimes:jpg,png,jpeg,svg',
                'actionTaken' => 'nullable',
            ]);

            if ($request->hasfile('aadhar')) {
                $file = $request->file('aadhar');
                $extension = $file->getClientOriginalExtension();
                $filename = 'pp.thesupernest.com/uploads/watchregister/aadhar/' . time() . '.' . $extension;
                $file->move('uploads/watchregister/aadhar', $filename);
                $data['aadhar'] = $filename;
            }
            if ($request->hasfile('photo')) {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                $filename = 'pp.thesupernest.com/uploads/watchregister/photo/' . time() . '.' . $extension;
                $file->move('uploads/watchregister/photo', $filename);
                $data['photo'] = $filename;
            }
            if ($request->hasfile('otherphoto')) {
                $file = $request->file('otherphoto');
                $extension = $file->getClientOriginalExtension();
                $filename = 'pp.thesupernest.com/uploads/watchregister/otherphoto/' . time() . '.' . $extension;
                $file->move('uploads/watchregister/otherphoto', $filename);
                $data['otherphoto'] = $filename;
            }

            $watch = Watchregister::where('id', $watchid)->update($data);
            return response()->json(["message" => "Success", "data" => $watch], 200);
        } else {
            return response()->json(["message" => "You are not authorized person."], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $watchid = $request->watchid;
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $getppid = Watchregister::where('id', $watchid)->pluck('ppid');
        $getppid = trim($getppid, '[]',);
        $getppid = (int)$getppid;

        $getpsid = Watchregister::where('id', $watchid)->pluck('ppid');
        $getpsid = trim($getpsid, '[]',);
        $getpsid = (int)$getpsid;

        if ($getppid == $uid or $getpsid == $uid) {
            $watch = Watchregister::find($watchid);
            $watch->delete();
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
            $data = Watchregister::orderBy('id', 'desc')->where('ppid', $ppid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 404);
        }
    }

    public function showbypsid($psid)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;

        if ($psid == $uid) {
            $data = Watchregister::orderBy('id', 'desc')->where('psid', $psid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["error" => "Your Not authorised Person"], 200);
        }
    }
}
