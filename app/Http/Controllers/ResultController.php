<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Result;
use App\Models\TrackPoint;
use App\Services\ResultService;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request, Result $result)
    {
        dd($result->resultsOverall($request->eventId));

        return view('result.index');
    }

    public function resultUser(Request $request, Result $result)
    {
        $result->resultsIndividual($request->registrationId);

        return view('result.index');
    }





    public function resultMap(Request $request, TrackPoint $trackPoint)
    {
        $data = '';
        foreach ($trackPoint::select('latitude', 'longitude')->where('result_id', $request->resultId)->get() as $trackPoint) {
            $data .= '<trkpt lat="'.$trackPoint->latitude.'" lon="'.$trackPoint->longitude.'">';
            $data .= '<ele></ele>';
            $data .= '<time></time>';
            $data .= '</trkpt>';
        }

        return response()->json($data);
    }

}
