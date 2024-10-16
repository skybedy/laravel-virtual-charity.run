<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Result;
use App\Models\TrackPoint;
use App\Services\ResultService;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Geometry\Factories\RectangleFactory;
use Intervention\Image\Typography\FontFactory;


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
        return response()->json($result->resultsIndividual($request->registrationId,$request->eventTypeId));
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

    public function shareFacebook(Request $request, Result $result)
    {

        $manager = new ImageManager(Driver::class);



        // create new image 512x512 with grey background
        $image = $manager->create(1200, 630)->fill('ccc');



        $image->drawRectangle(0, 0, function (RectangleFactory $rectangle) {
            $rectangle->size(1200, 120); // width & height of rectangle
            $rectangle->background('orange'); // background color of rectangle
           // $rectangle->border('white', 2); // border color & size of rectangle





        });

        $image->text('The quick brown fox', 120, 100, function (FontFactory $font) {

            $font->size(70);
            $font->color('fff');
            $font->stroke('ff5500', 2);
            $font->align('center');
            $font->valign('middle');
            $font->lineHeight(1.6);
            $font->angle(10);
            $font->wrap(250);
        });

        $image->save(public_path('images/test.png'));





        $result = $result->find($request->result_id);

        return view('result.share-facebook', [
            'result' => $result
        ]);
    }

}
