<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Result;
use App\Models\Startlist;
use App\Services\ResultService;
use App\Exceptions\SmallDistanceException;
use App\Exceptions\TimeIsOutOfRangeException;
use App\Exceptions\DuplicateFileException;
use App\Models\TrackPoint;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

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
        
    }}
