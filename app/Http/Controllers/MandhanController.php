<?php

namespace App\Http\Controllers;

use App\Mandhan;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;

class MandhanController extends Controller
{
    public function store(Request $request)
    {
        $start = Carbon::now()->startOfMonth()->format('d-m-Y');
        $end = Carbon::now()->endOfMonth()->format('d-m-Y');
        $date = Carbon::now()->format('m-Y');
        $today = date('d-m-Y');
        $ppid = $request->id;
        $count = $request->count;
        $name = User::where('id', $ppid)->get('name');
        $name = $name[0]['name'];
        $village = User::where('id', $ppid)->get('village');
        $village = $village[0]['village'];
        $taluka = User::where('id', $ppid)->get('taluka');
        $taluka = $taluka[0]['taluka'];
        $link = "http://127.0.0.1:8000/api/mandhan/certificate?start=$start&end=$end&taluka=$taluka&village=$village&name=$name&count=$count&today=$today";
        $psid = User::where('id', $ppid)->get('psid');
        $psid = $psid[0]['psid'];

        $data = array('date' => $date, 'ppid' => $ppid, 'psid' => $psid, 'link' => $link);
        $data = Mandhan::create($data);
        return response()->json(["message" => "Success", "link" => $link], 200);
    }

    public function show(Request $request)
    {
        $name = $request->name;
        $start = $request->start;
        $end = $request->end;
        $village = $request->village;
        $taluka = $request->taluka;
        $count = $request->count;
        $today = $request->today;
        return view('mandhan', compact('name', 'start', 'end', 'village', 'count', 'taluka', 'today'));
    }

    public function index(Request $request)
    {
        $loggedinuser = auth()->guard('api')->user();
        $uid = $loggedinuser->id;
        $userRole = $loggedinuser->role;
        $psid = $loggedinuser->psid;

        if ($userRole == 'admin') {

            $q = $request->query();
            if ($q != NULL) {
                $psid = $request->psid;
                $village = $request->village;
                $my_query = Mandhan::query();
                foreach ($request->query() as $key => $value) {
                    $my_query->where($key, $value);
                }
                $data = $my_query->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Mandhan::get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'ps') {
            $psid = $loggedinuser->psid;
            $ppid = $request->ppid;
            if (!empty($ppid)) {
                $data = Mandhan::where('ppid', $ppid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            } else {
                $data = Mandhan::where('psid', $psid)->get();
                return response()->json(["message" => "Success", "data" => $data], 200);
            }
        } else if ($userRole == 'pp') {
            $data = Mandhan::where('ppid', $uid)->get();
            return response()->json(["message" => "Success", "data" => $data], 200);
        } else {
            return response()->json(["message" => "You are not authorized person.l̥"], 200);
        }
    }
}
