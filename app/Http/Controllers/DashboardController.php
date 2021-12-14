<?php

namespace App\Http\Controllers;

use App\Crimeregister;
use Illuminate\Http\Request;
use App\Illegalworkregister;
use DB;
use App\Movementregister;
use App\Watchregister;
use App\Points;
use App\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $loggedinuser = auth()->guard('api')->user();
        $role = $loggedinuser->role;
        // return $role;
        if ($role == 'admin') {
            // Top 10 Police patil
            $top10pp = Points::orderBy('points', 'DESC')->limit(10)->pluck('ppid');

            $psname = $this->getpp($top10pp);
            // return $psname;

            // Latest Illeagal Work
            $latestillegalwork = Illegalworkregister::latest()->take(10)->get();

            // Latest Watch
            $latestwatch = Watchregister::latest()->take(10)->get();

            // Latest Movement
            $latestmovement = Movementregister::latest()->take(10)->get();

            //Crime Graph
            $ps = $request->crimeps;
            $village = $request->crimevillage;

            if (!empty($ps) and !empty($village)) {
                $graphcrime = Crimeregister::select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("MONTHNAME(created_at) as month_name")
                )
                    ->where('psid', $ps)
                    ->where('ppid', $village)
                    ->whereYear('created_at', date('Y'))
                    ->groupBy('month_name')
                    ->get()
                    ->toArray();
            } else if (!empty($ps)) {
                // return "ps";
                $graphcrime = Crimeregister::select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("MONTHNAME(created_at) as month_name")
                )
                    ->where('psid', $ps)
                    ->whereYear('created_at', date('Y'))
                    ->groupBy('month_name')
                    ->get()
                    ->toArray();
            } else {
                $graphcrime = Crimeregister::select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("MONTHNAME(created_at) as month_name")
                )
                    ->whereYear('created_at', date('Y'))
                    ->groupBy('month_name')
                    ->get()
                    ->toArray();
            }

            // Movement Graph

            $mps = $request->movementps;
            $mvillage = $request->movementvillage;

            if (!empty($mps) and !empty($mvillage)) {
                $graphmovement = Movementregister::select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("MONTHNAME(created_at) as month_name")
                )
                    ->where('psid', $mps)
                    ->where('ppid', $mvillage)
                    ->whereYear('created_at', date('Y'))
                    ->groupBy('month_name')
                    ->get()
                    ->toArray();
            } else if (!empty($mps)) {
                // return "ps";
                $graphmovement = Movementregister::select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("MONTHNAME(created_at) as month_name")
                )
                    ->where('psid', $mps)
                    ->whereYear('created_at', date('Y'))
                    ->groupBy('month_name')
                    ->get()
                    ->toArray();
            } else {
                $graphmovement = Movementregister::select(
                    DB::raw("(COUNT(*)) as count"),
                    DB::raw("MONTHNAME(created_at) as month_name"),
                    DB::raw("type as month_type")
                )
                    ->whereYear('created_at', date('Y'))
                    ->groupBy('month_name', 'type')
                    ->get()
                    ->toArray();
            }

            $data = array('top10pp' => $psname, 'latestillegalwork' => $latestillegalwork, 'latestwatch' => $latestwatch, 'latestmovement' => $latestmovement, 'graphcrime' => $graphcrime, 'graphmovement' => $graphmovement);
            return response()->json(["message" => "Sucees", "data" => $data], 200);
        } else if ($role == 'ps') {

            $psid = $loggedinuser->psid;

            // Top 10 Police patil
            $top10pp = Points::orderBy('points', 'DESC')->limit(10)->get('ppid');
            $psname = $this->getpp($top10pp);

            // Latest Illeagal Work
            $latestillegalwork = Illegalworkregister::where('psid', $psid)->latest()->take(10)->get();

            // Latest Watch
            $latestwatch = Watchregister::where('psid', $psid)->latest()->take(10)->get();

            // Latest Movement
            $latestmovement = Movementregister::where('psid', $psid)->latest()->take(10)->get();

            // $gillegalwork = Illegalworkregister::select(DB::raw('MONTH(created_at) as month')->keyBy('month')->count()->get());
            $graphcrime = Crimeregister::select(
                DB::raw("(COUNT(*)) as count"),
                DB::raw("MONTHNAME(created_at) as month_name")
            )
                ->where('psid', $psid)
                ->whereYear('created_at', date('Y'))
                ->groupBy('month_name')
                ->get()
                ->toArray();

            $graphmovement = Movementregister::select(
                DB::raw("(COUNT(*)) as count"),
                DB::raw("MONTHNAME(created_at) as month_name")
            )
                ->where('psid', $psid)
                ->whereYear('created_at', date('Y'))
                ->groupBy('month_name')
                ->get()
                ->toArray();

            $data = array('top10pp' => $psname, 'latestillegalwork' => $latestillegalwork, 'latestwatch' => $latestwatch, 'latestmovement' => $latestmovement, 'graphcrime' => $graphcrime, 'graphmovement' => $graphmovement);
            return response()->json(["message" => "Sucees", "data" => $data], 200);
        } else {
            return response()->json(["message" => "Your not authorised person"], 404);
        }
    }
    public function getpp($ppid)
    {
        $data = trim($ppid, '{}[]',);
        $data = User::whereIn('id', $ppid)->orderByRaw("FIELD(id, $data)")->get();
        return $data;
    }
}
