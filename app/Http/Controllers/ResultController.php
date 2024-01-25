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

    public function upload(Request $request, ResultService $resultService, Registration $registration)
    {

        $eventId = $request->event_id;
        $userId = $request->user()->id;

        //todo zmemena ta metoda pod tom
        //  dd($registration->registrationExists($eventId,$userId)->id);
        $finishTime = $resultService->finishTime($request);
        // dd($finishTime);

        $result = new Result();
        $result->registration_id = $registration->registrationExists($request->event_id, $request->user()->id)->id;
        $result->finishtime_order = $result->finishTimeOrder($request->registration_id);
        $result->finishtime = $finishTime['finish_time'];
        $result->finishtime_sec = $finishTime['finish_time_sec'];
        $result->save();

        dd($result);

    }
}
