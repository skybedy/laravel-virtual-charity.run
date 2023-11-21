<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Event;

class ResultService 
{
    
   
  
  public function  insertResult($request,$finishTime)
  {
   
   /*
    $eventId = $request->event_id;
    $userId = $request->user()->id;
    $registrationId = $this->registrationId($eventId,$userId);
    $result = new Result;
    $result->registration_id = $registrationId;
    $result->finish_time = $finishTime['finish_time'];
    $result->finish_time_sec = $finishTime['finish_time_sec'];
    $result->save();*/
  }



  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  public function finishTime($request) 
    {
        
        $file = $request->file('file');
        $destinationPath = 'uploads';
        $file->move($destinationPath,$file->getClientOriginalName());
        $eventLength = $this->eventLength($request->event_id);
        $xmlString = file_get_contents($destinationPath.'/'.$file->getClientOriginalName());
        $xmlObject = simplexml_load_string($xmlString);
        $lastPointLat = null;
        $lastPointLon = null;
        $currentPointLat = null;
        $currentPointLon = null;
        $distance = 0;
        $i = 1;;
        foreach($xmlObject->trk->trkseg->trkpt as $point)
        {
          if($i == 1)
          {
             $startDayTimestamp = $this->startDayTimestamp($point->time);
          }
          
          $lastPointLat = $currentPointLat;
          $lastPointLon = $currentPointLon;
          $currentPointLat = floatval($point['lat']);
          $currentPointLon = floatval($point['lon']);

          if($lastPointLat != null)
          {
            $pointDistance = $this->haversineGreatCircleDistance($lastPointLat, $lastPointLon, $currentPointLat, $currentPointLon);
            $distance += $pointDistance;

            if($distance > $eventLength)
            {
              $finishTime = $this->finishTimeCalculation($point,$distance,$startDayTimestamp,$eventLength);
              break;
            }

          }

          $i++;
        }

        return [
            'finish_time' => $finishTime['finish_time'],
            'finish_time_sec' => $finishTime['finish_time_sec']
          ];
    }


    private function finishTimeCalculation($point,$distance,$startDayTimestamp,$eventLength)
    {
        $t = new Carbon($point->time);
        $finishDayTimestamp = $t->timestamp;
        $rawFinishTimeSec = $finishDayTimestamp - $startDayTimestamp;
        $speedMeterPerSec = $distance / $rawFinishTimeSec;
        $plusDistance = $distance - $eventLength;
        $finishTimeSec = $rawFinishTimeSec - ($plusDistance * $speedMeterPerSec);
        $carbonFinishTime = Carbon::createFromTime(0, 0, 0);
        $carbonFinishTime->addSeconds($finishTimeSec);
        $finishTime = $carbonFinishTime->format('H:i:s');

        return[
          'finish_time' => $finishTime,
          'finish_time_sec' => intval(round($finishTimeSec,0)),
        ];
        
    }



    private function startDayTimestamp($time)
    {
        $t = new Carbon($time);
        return $t->timestamp;
    }

   
   
    private function eventLength($eventId)
    {
        return Event::where('id',$eventId)->value('length');
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
  private function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {

      $latFrom = deg2rad($latitudeFrom);
      $lonFrom = deg2rad($longitudeFrom);
      $latTo = deg2rad($latitudeTo);
      $lonTo = deg2rad($longitudeTo);
    
      $latDelta = $latTo - $latFrom;
      $lonDelta = $lonTo - $lonFrom;
    
      $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
      return $angle * $earthRadius;
    }

}