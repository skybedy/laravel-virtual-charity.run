<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Event $event)
    {
        if($request->user() == null)
        {
            return view('events.index', [
                'events' => $event::All(),
            ]);
        }
        else
        {
            return view('events.index', [
                'events' => $event->eventList($request->user()->id),
            ]);
        }
        
    }

    public function show(Request $request,Event $event)
    {
        return view('events.show', [
            'event' => $event::find($request->eventId),
        ]);
    }

    public function uploadCreate(Request $request,Event $event)
    {
        return view('events.results.upload-create', [
            'event' => $event::find($request->eventId),
        ]);
    }

    public function uploadStore(Request $request, ResultService $resultService,Registration $registration,TrackPoint $trackPoint,Event $event)
    {
        
        if(isset($registration->registrationExists( $request->eventId, $request->user()->id)->id))
        {
            $registration_id = $registration->registrationExists( $request->eventId, $request->user()->id)->id;
        }
        else
        {
            return back()->withError('Nahrávat výsledky je možné až poté, co se k závodu zaregistrujete')->withInput();
        }
        
       
       
       
        $request->validate([
            'place' => 'required|string|max:100',
        ]);
        
        
        try 
        {
            $finishTime = $resultService->finishTime($request);
        } 
        catch (SmallDistanceException $e)
        {
            return back()->withError($e->getMessage())->withInput();
        }
        catch (TimeIsOutOfRangeException $e)
        {
            return back()->withError($e->getMessage())->withInput();
        }
        catch (DuplicateFileException $e)
        {
            return back()->withError($e->getMessage())->withInput();
        }
        catch (UniqueConstraintViolationException $e) {
            //dd('tu');
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                // Duplicitní záznam byl nalezen, zde můžete provést potřebné akce
                // Například můžete záznam přeskočit, aktualizovat nebo vrátit chybovou zprávu uživateli
            }
        }


      




  





       

            $result = new Result();
            $result->registration_id = $registration_id;
            //$result->finish_time_order = $result->finishTimeOrder($request->registration_id);
           // dd( $result->finish_time_order);
            $result->finish_time_date = $finishTime['finish_time_date'];
            $result->finish_time = $finishTime['finish_time'];
            $result->finish_time_sec = $finishTime['finish_time_sec'];  
            $result->duplicity_check = $finishTime['duplicity_check'];  
            $result->place = $request->place; 

          //  DB::transaction(function () use ($result,$finishTime,$trackPoint) {
                DB::beginTransaction();

                
                try{
                    $result->save();
                }
                catch(QueryException $e)

                {
                    return back()->withError('Došlo k problému s nahráním souboru, kontaktujte timechip.cz@gmail.com')->withInput();;
                }


        for($i = 0; $i < count($finishTime['track_points']); $i++)
        {
            $finishTime['track_points'][$i]['result_id'] = $result->id;
        }



        
        try{
            $trackPoint::insert($finishTime['track_points']);
            DB::commit();
        }
        catch (UniqueConstraintViolationException $e) 
        {
            if($e->errorInfo[1] == 1062)
            {
                DB::rollback();
                return back()->withError('Soubor obsahuje duplicitní časové údaje')->withInput();
            }
        }
       
        $r = Result::where('registration_id', $registration_id)
        ->orderBy('finish_time', 'asc')
        ->get();




$lastId = $result->id;
foreach($r as $key => $value)
{
    if($value->id == $lastId)
    {
        $rank = $key + 1;
    }

    Result::where('id', $value->id)->update(['finish_time_order' => $key + 1]);



}


/*
if($rank == 1)
{
    dd("Je to zatím tvůj nejlepší čas v tomto závodě a bude zařazen do celkových výsledků");
}
else
{
   // dd("Je to celkově tvůj $rank.čas v tomto závodě a do celkových výsledků se započítávat nebude, seznam všech tvých časů je v tabulce");
    return view('events.results.post-upload', [
        'results' =>  Result::where('registration_id', $registration_id)
        ->orderBy('finish_time_sec', 'asc')
        ->get(),
    ]);

}*/


return view('events.results.post-upload', [
    'results' =>  Result::selectRaw('id,DATE_FORMAT(finish_time_date,"%e.%c") AS date,place,finish_time')
    ->where('registration_id', $registration_id)
    ->orderBy('finish_time', 'asc')
    ->get(),
    'event' => $event::find($request->eventId),
    'last_id' => $lastId,
    'rank' => $rank
]);     


    
       
       

    }



    public function resultIndex(Request $request,Result $result,Event $event)
    {
        return view('events.results.result-index', [
            'results' =>  $result->resultsOverall($request->eventId),
            'event' => $event::find($request->eventId),
        ]);
    }

    public function startlistIndex(Request $request,Registration $registration,Event $event,Startlist $startlist)
    {
        return view('events.start-list', [
            'startlists' => $startlist->startlist($request->eventId),
            'event' => $event::find($request->eventId),
        ]);
    }

}
