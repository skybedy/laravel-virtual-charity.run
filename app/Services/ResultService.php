<?php

namespace App\Services;

use App\Exceptions\DuplicateFileException;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Result;
use App\Exceptions\SmallDistanceException;
use App\Exceptions\TimeIsOutOfRangeException;
use App\Exceptions\DuplicityException;
use App\Models\TrackPoint;
use App\Models\Registration;
use Illuminate\Support\Facades\Log;

class ResultService 
{
   
  private $eventDistance;
  private $dateStart;
  private $dateEnd;
  private $dateEventStartTimestamp;
  private $dateEventEndTimestamp;
  private $duplicityCheck;

  
  
  public function __construct()
  {
    //  $event = Event::where('id',$eventId);
      //$this->eventDistance = $event->value('Distance');
      //$this->dateEventStartTimestamp = Carbon::createFromFormat('Y-m-d', $event->value('date_start'))->timestamp;
      //$this->dateEventEndTimestamp = Carbon::createFromFormat('Y-m-d', $event->value('date_end'))->timestamp;

  }

  
  private function duplicityCheck($userId,$time)
  {
    $result = new Result();
        
    $allCheckTimesTogether = [];
   
    $allCheckTimesFromDb = $result->getDuplicityCheck($userId);
   
   
    foreach($allCheckTimesFromDb as $allCheckTimesArrays)
    {
      foreach($allCheckTimesArrays as $checkTimesJson)
      {
          $checkTimesArray = json_decode($checkTimesJson);
          
          if(is_array($checkTimesArray))
          {
            $allCheckTimesTogether = array_merge($allCheckTimesTogether,$checkTimesArray);
          }
      }
    }

    if(in_array($this->iso8601ToTimestamp($time),$allCheckTimesTogether))
    {
      return false;
    }
    else
    {
      return true;
    }

  }



    public function overallDistance($request,$registration)
    {
        
        $trackPointArray = [];
        $file = $request->file('file');
        
      
        
        $xmlObject = simplexml_load_file($file);
        
        
        
        $lastPointLat = null;
        $lastPointLon = null;
        $currentPointLat = null;
        $currentPointLon = null;
        $distance = 0;
     
        $originalDateTime = $xmlObject->metadata->time;


         $activityDate  = Carbon::parse($originalDateTime)->format('Y-m-d');    
        
        $events = Event::where('date_start', '>=',$activityDate)
                        ->where('date_end', '>=',$activityDate)
                        ->orderBy('distance','DESC')
        ->get(['id','distance']);

     

        // iteration through gpx
        $i = 1;
        foreach($xmlObject->trk->trkseg->trkpt as $point)
        {
          
          
          
          
          if($i == 1)
          {
            $time = $this->iso8601ToTimestamp($point->time);
            $startDayTimestamp = $time;
           // dd($startDayTimestamp);
          }


          
          
          
            $lastPointLat = $currentPointLat;
            $lastPointLon = $currentPointLon;
            $currentPointLat = floatval($point['lat']);
            $currentPointLon = floatval($point['lon']);

        





            if($lastPointLat != null)
            {
                $pointDistance = $this->vincentyGreatCircleDistance($lastPointLat, $lastPointLon, $currentPointLat, $currentPointLon);
                $distance += $pointDistance;
            }

            $trackPointArray[] = [
              'distance' => $distance,
              'time' => $point->time,
              'user_id' => $request->user()->id,
              'trkpt' =>  
              [
                'latitude' => $currentPointLat,
                'longitude' => $currentPointLon,
                'elevation' => $point->ele]
              ];

            $i++;
        }






        foreach($events as $event)
        {
            dump($event);
            if($distance >= $event['distance'])
            {
                
                if(isset($registration->registrationExists( $event['id'], $request->user()->id)->id))
                {
                    $registration_id = $registration->registrationExists( $event['id'], $request->user()->id)->id;
                    //dump( $registration_id );

                
            
                
                
                
                $this->eventDistance = $event['distance'];

                foreach($trackPointArray as $trackPoint)
                {
                    if($trackPoint['distance'] >= $event['distance'])
                    {
                        
                        $finishTime = $this->finishTimeCalculation($trackPoint['time'],$trackPoint['distance'],$startDayTimestamp);
                        break;
                    }
                }

           
                dd($finishTime);

        
        
                return [
                  'finish_time' => $finishTime['finish_time'],
                  'finish_time_sec' => $finishTime['finish_time_sec'],
                  'average_time_per_km' => $finishTime['average_time_per_km'],
                  'track_points' => $trackPointArray,
                ];
           
                
            }

                
                
                else
                {
                    dump('neni');
                   
                    Log::info('Event '.$event['id'].' délkově odpovídá, ale uživatel id '.$request->user()->id.' k nemu není přihlášený'); 
                    continue;
                }
                


              
              
              
              
                break;
              
              
            }
          
        }
       
       
       
       
     

        


          
    }



    private function finishTimeCalculation($time,$distance,$startDayTimestamp)
    {
       
        $t = new Carbon($time);
       
        $finishDayTimestamp = $t->timestamp;
        
        $rawFinishTimeSec = $finishDayTimestamp - $startDayTimestamp;
        
        
        $speedMeterPerSec = $distance / $rawFinishTimeSec;
       
        $plusDistance = $distance - $this->eventDistance;


        $finishTimeSec = $rawFinishTimeSec - ($plusDistance * (1 / $speedMeterPerSec));


       

        $carbonFinishTime = Carbon::createFromTime(0, 0, 0);
        $carbonFinishTime->addSeconds($finishTimeSec);
        $finishTime = $carbonFinishTime->format('H:i:s');

        return[
          'finish_time' => $finishTime,
          'finish_time_sec' => intval(round($finishTimeSec,0)),
          'average_time_per_km' => $this->averageTimePerKm($finishTimeSec)
        ];
        
    }


    
    
    













  public function finishTime($request)
    {
       
      //dd($request->eventId);


        $event = Event::where('id',$request->eventId);
        $this->eventDistance = $event->value('distance');
        $this->dateEventStartTimestamp = Carbon::createFromFormat('Y-m-d', $event->value('date_start'))->timestamp;
        $this->dateEventEndTimestamp = Carbon::createFromFormat('Y-m-d', $event->value('date_end'))->timestamp;


        $trackPointArray = [];
        $file = $request->file('file');
        $destinationPath = 'uploads';
        $file->move($destinationPath,$file->getClientOriginalName());
        $xmlString = file_get_contents($destinationPath.'/'.$file->getClientOriginalName());
        $xmlObject = simplexml_load_string($xmlString);
        $lastPointLat = null;
        $lastPointLon = null;
        $currentPointLat = null;
        $currentPointLon = null;
        $distance = 0;
     
        $originalDateTime = $xmlObject->metadata->time;
        $finishTimeDate = date("Y-m-d", strtotime($originalDateTime));
        $randomNumbers = $this->generateRandomNumbers();
      //  dd($randomNumbers);
         $duplicityCheck = [];
        

        $i = 1;
        foreach($xmlObject->trk->trkseg->trkpt as $point)
        {
          
          $time = $this->iso8601ToTimestamp($point->time);
          
          
          if(!$this->isTimeInRange($time))
          {
            throw new TimeIsOutOfRangeException('Čas je mimo rozsah akce.');
          }
          

          /*
          if(!$this->duplicityCheck($request->user()->id,$point->time))
          {
            throw new DuplicateFileException('Soubor obsahuje duplicitní časové údaje.');
          }*/
          
          
          if($i == 1)
          {
             $startDayTimestamp = $time;
          }

          
          /*
          if($i == $randomNumbers[0] || $i == $randomNumbers[1])
          {
            $duplicityCheck[] = $time;
          }*/

          
          $lastPointLat = $currentPointLat;
          $lastPointLon = $currentPointLon;
          $currentPointLat = floatval($point['lat']);
          $currentPointLon = floatval($point['lon']);

          $trackPointArray[] = [
            'latitude' => $currentPointLat,
            'longitude' => $currentPointLon,
            'time' => $time,
            'elevation' => $point->ele,
            'user_id' => $request->user()->id,
          ];





          if($lastPointLat != null)
          {
            $pointDistance = $this->vincentyGreatCircleDistance($lastPointLat, $lastPointLon, $currentPointLat, $currentPointLon);
            $distance += $pointDistance;

            if($distance >= $this->eventDistance)
            {
              $finishTime = $this->finishTimeCalculation($point->time,$distance,$startDayTimestamp);
              break;
            }

          }

          $i++;
        }


        







        if($distance < $this->eventDistance)
        {
          throw new SmallDistanceException('Vzdálenost je menší než délka tratě.');
        }
        else
        {
          
          return [
            'finish_time' => $finishTime['finish_time'],
            'finish_time_sec' => $finishTime['finish_time_sec'],
            'average_time_per_km' => $finishTime['average_time_per_km'],
            'track_points' => $trackPointArray,
          ];

        }
    }


    
    
    
    
    
    
  




    private function averageTimePerKm($finishTimeSec)
    {
        $secondPerKm = round(($finishTimeSec*1000)/$this->eventDistance);
        $timeObj = Carbon::createFromTime(0, 0, 0)->addSeconds($secondPerKm);

        return substr($timeObj->format('i:s'),1);
    }

    function isTimeInRange($time)
    {
      if ($time >= $this->dateEventStartTimestamp && $time <= $this->dateEventEndTimestamp) {
        return true;
      }
      else
      {
        return false;
      }
    }




    private function iso8601ToTimestamp($time)
    {
        $t = new Carbon($time);
        return $t->timestamp;
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



    private function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {

      // Konverze stupňů na radiány
      $latFrom = deg2rad($latitudeFrom);
      $lonFrom = deg2rad($longitudeFrom);
      $latTo = deg2rad($latitudeTo);
      $lonTo = deg2rad($longitudeTo);
  
      // Ellipsoid konstanty
      $a = 6378137; // poloměr
      $b = 6356752.314245; // polární poloměr
      $f = 1 / 298.257223563; // zploštění
  
      $L = $lonTo - $lonFrom;
      $U1 = atan((1 - $f) * tan($latFrom));
      $U2 = atan((1 - $f) * tan($latTo));
  
      $sinU1 = sin($U1);
      $cosU1 = cos($U1);
      $sinU2 = sin($U2);
      $cosU2 = cos($U2);
  
      $lambda = $L;
      $lambdaP = 2 * M_PI;
      $iterLimit = 20;
  
      while (abs($lambda - $lambdaP) > 1e-12 && --$iterLimit > 0) {
          $sinLambda = sin($lambda);
          $cosLambda = cos($lambda);
          $sinSigma = sqrt(($cosU2 * $sinLambda) * ($cosU2 * $sinLambda) + ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda) * ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLambda));
          if ($sinSigma == 0) {
              return 0; // coincident points
          }
  
          $cosSigma = $sinU1 * $sinU2 + $cosU1 * $cosU2 * $cosLambda;
          $sigma = atan2($sinSigma, $cosSigma);
          $sinAlpha = $cosU1 * $cosU2 * $sinLambda / $sinSigma;
          $cosSqAlpha = 1 - $sinAlpha * $sinAlpha;
          $cos2SigmaM = $cosSigma - 2 * $sinU1 * $sinU2 / $cosSqAlpha;
          if (is_nan($cos2SigmaM)) {
              $cos2SigmaM = 0; // equatorial line
          }
          $C = $f / 16 * $cosSqAlpha * (4 + $f * (4 - 3 * $cosSqAlpha));
          $lambdaP = $lambda;
          $lambda = $L + (1 - $C) * $f * $sinAlpha * ($sigma + $C * $sinSigma * ($cos2SigmaM + $C * $cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM)));
      }
  
      if ($iterLimit == 0) {
          return -1; // formula failed to converge
      }
  
      $uSq = $cosSqAlpha * ($a * $a - $b * $b) / ($b * $b);
      $A = 1 + $uSq / 16384 * (4096 + $uSq * (-768 + $uSq * (320 - 175 * $uSq)));
      $B = $uSq / 1024 * (256 + $uSq * (-128 + $uSq * (74 - 47 * $uSq)));
      $deltaSigma = $B * $sinSigma * ($cos2SigmaM + $B / 4 * ($cosSigma * (-1 + 2 * $cos2SigmaM * $cos2SigmaM) - $B / 6 * $cos2SigmaM * (-3 + 4 * $sinSigma * $sinSigma) * (-3 + 4 * $cos2SigmaM * $cos2SigmaM)));
  
      $s = $b * $A * ($sigma - $deltaSigma);
  
      return $s; // vrací vzdálenost v metrech
  }


  private function generateRandomNumbers()
  {
    $min = 13;
    $max = 38;
    $randomNumbers = [];
    
    $randomNumber1 = rand($min,$max);
    $randomNumber2 = rand($min,$max);

    if($randomNumber1 == $randomNumber2)
    {
      $this->generateRandomNumbers();
    }
    else
    {
      $randomNumbers = [$randomNumber1,$randomNumber2];
      sort($randomNumbers);
      
      return $randomNumbers;
    }
  }

      
  






}