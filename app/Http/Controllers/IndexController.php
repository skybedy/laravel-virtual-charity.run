<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Services\ResultService;
use App\Models\Registration;
use App\Exceptions\SmallDistanceException;
use App\Exceptions\TimeIsOutOfRangeException;
use App\Exceptions\TimeMissingException;
use App\Exceptions\DuplicateFileException;
use App\Models\TrackPoint;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use App\Models\Result;


class IndexController extends Controller
{
    public function index(Request $request, Event $event)
    {
        
        if($request->user() == null)
        {
            return view('index.index', [
                'events' => $event::All(),
            ]);
        }
        else
        {
            return view('index/index', [
                'events' => $event->eventList($request->user()->id),
            ]);
        }
        
    }

    public function autodistanceUpload(Request $request,ResultService $resultService,Registration $registration,TrackPoint $trackPoint,Event $event)
    {
        $request->validate([
            'place' => 'required|string|max:100',
        ]);
        
        /*
       try 
       {
           $finishTime = $resultService->overallDistance($request,$registration);
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
       catch (TimeMissingException $e)
       {
           return back()->withError($e->getMessage())->withInput();
       }
       catch (UniqueConstraintViolationException $e) {
           //dd('tu');
           $errorCode = $e->errorInfo[1];
           if($errorCode == 1062){
               // Duplicitní záznam byl nalezen, zde můžete provést potřebné akce
               // Například můžete záznam přeskočit, aktualizovat nebo vrátit chybovou zprávu uživateli
             //  dd($errorCode);
           }
       }*/




       $finishTime = $resultService->overallDistance($request,$registration);



       $result = new Result();
       $result->registration_id = $finishTime['registration_id'];
       $result->finish_time_date = $finishTime['finish_time_date'];
       $result->finish_time = $finishTime['finish_time'];
       $result->average_time_per_km = $finishTime['average_time_per_km'];
       $result->finish_time_sec = $finishTime['finish_time_sec'];  
      // $result->duplicity_check = $finishTime['duplicity_check'];  
       $result->place = $request->place; 

     //  dd($result);

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


      // $trackPoint::insert($finishTime['track_points']);
       
       try{
           $trackPoint::insert($finishTime['track_points']);
           DB::commit();
       }
       catch (UniqueConstraintViolationException $e) 
       {
           
        dd($e);
        if($e->errorInfo[1] == 1062)
           {
               DB::rollback();
               return back()->withError('Soubor obsahuje duplicitní časové údaje')->withInput();
           }
       }
      
       $r = Result::where('registration_id', $finishTime['registration_id'])
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






    }



}
