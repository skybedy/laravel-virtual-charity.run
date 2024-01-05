<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Services\ResultService;
use App\Models\Registration;


class IndexController extends Controller
{
    public function index(Request $request, Event $event)
    {
        if($request->user() == null)
        {
            return view('index', [
                'events' => $event::All(),
            ]);
        }
        else
        {
            return view('index', [
                'events' => $event->eventList($request->user()->id),
            ]);
        }
        
    }

    public function autodistanceUpload(Request $request,ResultService $resultService,Registration $registration)
    {
        $resultService->overallDistance($request,$registration);
        dd('bla');

    }



}
