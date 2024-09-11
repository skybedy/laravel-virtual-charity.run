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
     //   dd($result->resultsOverall($request->eventId));

        return view('result.index');
    }

    public function manage(Request $request,Result $result)
    {

       // dd($result->getAllUserResults($request->user()->id));
        return view('result.manage', [
            'results' => $result->getAllUserResults($request->user()->id)
        ]);
    }

    public function delete(Request $request)
    {
        TrackPoint::where('result_id', $request->resultId)->delete();

        Result::find($request->resultId)->delete();

        return back();
    }








    public function resultUser(Request $request, Result $result)
    {
        return response()->json($result->resultsIndividual($request->registrationId));
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
