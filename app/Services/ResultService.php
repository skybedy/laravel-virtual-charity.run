<?php

namespace App\Services;

use App\Exceptions\DuplicateFileException;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Result;
use App\Exceptions\SmallDistanceException;
use App\Exceptions\TimeIsOutOfRangeException;
use App\Exceptions\TimeMissingException;
use App\Exceptions\NoStravaAuthorizeException;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use App\Exceptions\DuplicityException;
use App\Exceptions\DuplicityTimeException;
use App\Exceptions\StravaPrivateException;
use App\Models\TrackPoint;
use App\Models\Registration;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Polyline;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Facades\DB;
use App\Model\Events;



class ResultService
{

    private $eventDistance;
    private $dateStart;
    private $dateEnd;
    private $dateEventStartTimestamp;
    private $dateEventEndTimestamp;
    private $duplicityCheck;

    public function getStreamFromStrava($request,$activityId = null): array
    {
        if($activityId == null)
        {
            $activityId = $request->input('object_id');

            $stravaId = $request->input('owner_id');

            $user = User::select('id', 'strava_access_token', 'strava_refresh_token', 'strava_expires_at')->where('strava_id', $stravaId)->first();

            $userId = $user->id;
        }
        else
        {
            $userId = $request->user()->id;

            $user = User::select('id', 'strava_access_token', 'strava_refresh_token', 'strava_expires_at')->where('id',$userId)->first();
        }
        //kontrola, jestli uzivatel ma autorizovanou aplikaci na Strave
        if(is_null($user->strava_access_token))
        {
            throw new NoStravaAuthorizeException();
        };

        //dd($user->strava_expires_at);

        if ($user->strava_expires_at > time())
        {
            $urlStream = config('strava.stream.url').$activityId.config('strava.stream.params');

            $token = $user->strava_access_token;

            $response = Http::withToken($token)->get($urlStream)->json();

            if ($response)
            {
                $urlActivity = config('strava.activity.url').$activityId.config('strava.activity.params');

                $response += Http::withToken($token)->get($urlActivity)->json();

                $response['user_id'] = $userId;
            }
        }
        else //TOKEN EXPIRED
        {   // URL na Stravu na vymenu tokenu
            $urlToken = config('strava.token.url');
            // parametry pro vymenu tokenu
            $params = config('strava.token.params');
            // doplneni parametru o refresh token
            $params['refresh_token'] = $user->strava_refresh_token;

            $responseToken = Http::post($urlToken,$params);

            $body = $responseToken->body();

            $content = json_decode($body, true);

            $user = new User();

            $token = $user->updateStravaToken($userId,$content);

            $urlStream = config('strava.stream.url').$activityId.config('strava.stream.params');

            $response = Http::withToken($token)->get($urlStream)->json();

            if ($response)
            {
                $urlActivity = config('strava.activity.url').$activityId.config('strava.activity.params');

                $response += Http::withToken($token)->get($urlActivity)->json();

                $response['user_id'] = $userId;
            }
        }

        return $response;
    }

    /**
     *  ziskani vysledkových dat z GPX souboru
     */
    public function getActivityFinishDataFromGpx($args)
    {
        $request = $args['request'];

        $registrationId = $args['registration_id'];

        $userId = $request->user()->id;

        $event = Event::where('id', $request->eventId);

        $eventDistance = $event->value('distance');

        $dateEventStartTimestamp = Carbon::createFromFormat('Y-m-d', $event->value('date_start'))->timestamp;

        $dateEventEndTimestamp = Carbon::createFromFormat('Y-m-d', $event->value('date_end'))->timestamp;

        $file = $request->file('gpx_file');

        $xmlObject = simplexml_load_string(trim($file->get()));
        //datum a cas zacatku aktivity z metadat
        //ziskani vsech namespace
        $namespaces = $xmlObject->getNamespaces(true);
        $activityDateTime = $xmlObject->metadata->time;
        // pokud není, tak to je spatne a takovy soubor neni mozne prijmout
        if ($activityDateTime == null)
        {
            throw new TimeMissingException();
        }
        //datum aktivity pro dotaz do DB
        $activityDate = date("Y-m-d", strtotime($activityDateTime));

        $distance = 0;

        $trackPointArray = [];

        $lastPointLat = $lastPointLon = $currentPointLat = $currentPointLon = $distance = null;

        $i = 1;
        //TODO ZRUSIT $i a dat to do foreach

        foreach ($xmlObject->trk->trkseg->trkpt as $point)
        {
            // kontrola, jestli je GPX obsahuje elementy time
            if (!isset($point->time)) {
                throw new TimeMissingException();
            }
            //prevedeni casu do Timestampu
            //TODO MUSI SE PREVADET VSECKY CASY?
            $time = $this->iso8601ToTimestamp($point->time);
            //kontrola, jestli je cas v rozsahu zavodu
            if (!$this->isTimeInRange($time, $dateEventStartTimestamp, $dateEventEndTimestamp))
            {
                throw new TimeIsOutOfRangeException('Aktivita je mimo rozsah akce.');
            }
            // zacatek startu aktivity, teoreticky by se měl shodovat s case v metadatech, ale pro jistotu
            if ($i == 1)
            {
                $startDayTimestamp = $time;
            }

            $lastPointLat = $currentPointLat;

            $lastPointLon = $currentPointLon;

            $currentPointLat = floatval($point['lat']);

            $currentPointLon = floatval($point['lon']);

            if(isset($namespaces['ns3']))
            {
                $cad = (string) $point->extensions->children($namespaces['ns3'])->TrackPointExtension->cad;
            }
            elseif(isset($namespaces['gpxtpx']))
            {
                $cad = (string) $point->extensions->children($namespaces['gpxtpx'])->TrackPointExtension->cad;
            }
            else
            {
                $cad = null;
            }
            //pridavani prvku do pole TrackPointArray
            $trackPointArray[] = [
                'latitude' => $currentPointLat,
                'longitude' => $currentPointLon,
                'time' => $time,
                'user_id' => $userId,
                'cadence' => (string) $cad,
                'registration_id' => $registrationId
            ];
            //pokud je to prvni, nebo prazdny bod, tak se nic nepocita

            if ($lastPointLat != null)
            {
                $pointDistance = $this->haversineGreatCircleDistance($lastPointLat, $lastPointLon, $currentPointLat, $currentPointLon);

                $distance += $pointDistance;
                //pokud načítaná vzdálenost je větší než délka závodu, tak se vypocita cas a dal se v cyklu, ktery prochazi souborem, nepokracuje
                if ($distance >= $eventDistance)
                {
                    $finishTime = $this->finishTimeCalculation($eventDistance,$distance, $point->time, $startDayTimestamp);

                    break;
                }
            }
            $i++;
        }
        // pokud skončí cyklus a vzdálenost je menší než délka závodu, tak se vyhodí vyjimka
        if ($distance < $eventDistance)
        {
            throw new SmallDistanceException('Vzdálenost je menší než délka tratě.');
        }
        else
        {
            //pokud to je ok, vrací se pole s výsledky
            return [
                'finish_time' => $finishTime['finish_time'],
                'finish_time_sec' => $finishTime['finish_time_sec'],
                'finish_time_date' => $activityDate,
                'pace' => $finishTime['pace'],
                'track_points' => $trackPointArray,
            ];
        }
    }





    /**
     * automaticke nahravani dat za Stravy
     */
    public function dataStravaProcessing($activityData, $registration)
    {


        $eventDate = Carbon::parse($activityData['start_date'])->format('Y-m-d');

        $events = Event::where('date_start', '<=', $eventDate)
        ->where('date_end', '>=', $eventDate)
        ->orderBy('distance', 'DESC')
        ->get(['id', 'distance']);

        $user = User::where('strava_id', 128967935)->value('id');    //value narozdil od first bere pouze potrebny sloupec
        // dd($user);

        if (!isset($events)) {
            //TODO dopsat vyjimku, ze neexistuje zadny zavod v urcenem casovem obdobi

            //dd("neni zadny zavod");
        }


        foreach ($events as $event) {
            // dump( $activityData['distance']);

            if ($activityData['distance'] >= $event['distance']) {


                //dd($event['id']);


                if (isset($registration->registrationExists($event['id'], $user)->id)) {
                    $registrationId = $registration->registrationExists($event['id'], $user)->id;
                    //dd( $registration_id );

                    $trackPoints = [];

                    $coordinates = Polyline::decode($activityData['map']['summary_polyline']);
                    // dd($coordinates);
                    foreach ($coordinates as $coordinate) {
                        $trackPoints[] = [
                            'latitude' => $coordinate[0],
                            'longitude' => $coordinate[1],
                            'user_id' => $user

                        ];
                    }

                    //delka jednotliveho zavodu uvedena v db
                    $this->eventDistance = $event['distance']; //bude lepsi poslat jako parametr, ne?
                    // $finishTime = $this->finishTimeCalculation($trackPoint['time'],$trackPoint['distance'],$startDayTimestamp);
                    $finishTime = $this->finishTimeCalculation($activityData['elapsed_time'], $activityData['distance']);

                    //dd($trackPoints);




                    return [
                        'finish_time' => $finishTime['finish_time'],
                        'finish_time_sec' => $finishTime['finish_time_sec'],
                        'pace' => $finishTime['pace'],
                        'track_points' => $trackPoints,
                        'registration_id' => $registrationId,
                        'finish_time_date' => $eventDate
                    ];


                } else {

                    // dump('neni prihlasen');
                    //uzivatel neni prihlasen k zavodu, ktery delkove vyhovuje
                }


            } else {
                //dump('zadna trat delkove nevyhovuje');
            }


        }


    }









    /**
     * zatím sloužilo pro simulaci nahrani post pozadavku ze stravy
     */

    public function getActivityFinishDataFromStravaStream($args)
    {

        $request = $args['request'];

        $userId = $request->user()->id;

        $registrationId = $args['registrationId'];

        $event = Event::where('id', $request->eventId);

        $eventDistance = $event->value('distance');

        $eventType = $event->value('event_type_id');

        $timeDistance = $event->value('time');

        $dateEventStartTimestamp = Carbon::createFromFormat('Y-m-d', $event->value('date_start'))->timestamp;

        $dateEventEndTimestamp = Carbon::createFromFormat('Y-m-d', $event->value('date_end'))->timestamp;

        $activityData = $args['activity_data'];

       // tohle usetruje situaci, kdy stream ze Stravy neobsahuje vsechny potrebne informace (v tomto pripade cas startu aktivity)
       // k čemuž docházů třeba v pripade, ze aktivita je soukromá, nebo se někdo pokousi nahrat aktivitu nekoho jineho, apod.
        if(!isset($activityData['start_date_local']))
        {
            throw new StravaPrivateException();
        }

        $startDayTimestamp = strtotime($activityData['start_date_local']);

        //datum aktivity pro dotaz do DB
        $activityDate = date("Y-m-d", $startDayTimestamp);




        //vytvoreni noveho pole se stejnymi paramatry jak GPX soubor
        $activityDataArray = [];

        // vytvoreni pole ve stejne strukture jak GPX soubor
        foreach ($activityData['latlng']['data'] as $key => $val)
        {
            $activityDataArray[] = [
                    'latlng' => $val,
                    'time' => $activityData['time']['data'][$key] + $startDayTimestamp,
                    'distance' => $activityData['distance']['data'][$key],
                    'altitude' => $activityData['altitude']['data'][$key],
                    'cadence' => $activityData['cadence']['data'][$key],
                    'seconds' => $activityData['time']['data'][$key],
                ];

        }

        $trackPointArray = [];

        $lastPointLat = $lastPointLon = $currentPointLat = $currentPointLon = $distance = null;


        //zavody na cas
        if($eventType == 2)
        {
             return $this->getActivityFinishDataFromStravaStreamTimeType($userId,$activityDataArray,$dateEventStartTimestamp,$dateEventEndTimestamp,$currentPointLat,$currentPointLon,$distance,$timeDistance,$activityDate);
        }
        // zavody na vzdalenost
        else
        {
            foreach($activityDataArray as $activityData)
            {

                if (!$this->isTimeInRange($activityData['time'], $dateEventStartTimestamp, $dateEventEndTimestamp))
                {
                    throw new TimeIsOutOfRangeException('Čas je mimo rozsah akce.');
                }

                $lastPointLat = $currentPointLat;

                $lastPointLon = $currentPointLon;

                $currentPointLat = floatval($activityData['latlng'][0]);

                $currentPointLon = floatval($activityData['latlng'][1]);

                //dump($lastPointLat);

                $trackPointArray[] = [
                    'latitude' => $currentPointLat,
                    'longitude' => $currentPointLon,
                    'time' => $activityData['time'],
                    'user_id' => $userId,
                    'cadence' => $activityData['cadence'],
                    'registration_id' => $registrationId
                ];


                if ($lastPointLat != null) {

                    $pointDistance = round($this->haversineGreatCircleDistance($lastPointLat, $lastPointLon, $currentPointLat, $currentPointLon), 1);

                    $distance += $pointDistance;


                    if ($distance >= $eventDistance)
                    {
                        $finishTime = $this->finishTimeCalculation($eventDistance,$activityData['distance'],$point['time'],$startDayTimestamp);

                        return [
                            'finish_time' => $finishTime['finish_time'],
                            'finish_time_sec' => $finishTime['finish_time_sec'],
                            'pace' => $finishTime['pace'],
                            'track_points' => $trackPointArray,
                          //  'registration_id' => $registrationId,
                            'finish_time_date' => $activityDate,
                        ];
                    }
                }
            }

            throw new SmallDistanceException('Vzdálenost je menší než délka tratě.');

        }

    }



    private function getActivityFinishDataFromStravaStreamTimeType($userId,$activityDataArray,$dateEventStartTimestamp,$dateEventEndTimestamp, $currentPointLat, $currentPointLon,$distance,$timeDistance,$activityDate)
    {


        foreach($activityDataArray as $activityData)
        {

            if (!$this->isTimeInRange($activityData['time'], $dateEventStartTimestamp, $dateEventEndTimestamp))
            {
                throw new TimeIsOutOfRangeException('Aktivita je mimo rozsah akce.');
            }

            $lastPointLat = $currentPointLat;

            $lastPointLon = $currentPointLon;

            $currentPointLat = floatval($activityData['latlng'][0]);

            $currentPointLon = floatval($activityData['latlng'][1]);


            $trackPointArray[] = [
                'latitude' => $currentPointLat,
                'longitude' => $currentPointLon,
                'time' => $activityData['time'],
                'user_id' => $userId,
                'cadence' => $activityData['cadence'],
            ];


            if ($lastPointLat != null) {

                $activityDataDistance = round($this->haversineGreatCircleDistance($lastPointLat, $lastPointLon, $currentPointLat, $currentPointLon), 1);

                $distance += $activityDataDistance;



                if ($activityData['seconds'] >= $timeDistance)
                {


                   // $finishTime = $this->finishTimeCalculation($timeDistance,$activityData['distance'],$activityData['time'],$startDayTimestamp);

                   $timeNavic = $activityData['seconds'] - $timeDistance;
                   $distanceCm = $distance * 100;
                   $cmZaSekundu = $distanceCm / $activityData['seconds'] ;
                   $cmNavic = $cmZaSekundu * $timeNavic;
                   $cmPoKorekci = $distanceCm - $cmNavic;
                   $metryPoKorekci = intval(round($cmPoKorekci / 100));

                   //dump(round(floatval($metryPoKorekci / 1000),2));
                   //dd(round(floatval(($metryPoKorekci * 0.8) / 1000),2));










                    return [
                  //      'finish_time' => $finishTime['finish_time'],
                    //    'finish_time_sec' => $finishTime['finish_time_sec'],
                    'finish_distance_km' => round(floatval($metryPoKorekci / 1000),2),
                    'finish_distance_mile' => round(floatval(($metryPoKorekci * 0.8) / 1000),2),

                       'pace' => $this->averageTimePerKm($distance,$timeDistance),
                       'pace_mile' => $this->pacePerMile($distance,$timeDistance),
                        'track_points' => $trackPointArray,
                      // 'registration_id' => $registrationId,
                        'finish_time_date' => $activityDate,
                    ];
                }
            }
        }

        throw new SmallDistanceException('Čas běhu je kratší než 1 hodina');

    }


    private function pacePerMile($eventDistance,$finishTimeSec)
    {
        $secondPerMile = round(($finishTimeSec * 1609.3) / $eventDistance);

        $timeObj = Carbon::createFromTime(0, 0, 0)->addSeconds($secondPerMile);

        if($secondPerMile > 599)
        {
            return $timeObj->format('i:s');
        }
        else
        {
            return substr($timeObj->format('i:s'), 1);
        }


    }







    //otazka zda spis nevyvolat vyjimky a logovat v controlleru, asi predelat
    public function getActivityFinishDataFromStravaWebhook($activityData, $registration, $userId)
    {
        
        
      //  print_r($activityData);

        //exit();
        
        
        
        
        $userRegisteredToSomeEvent = false;
        //pocatecni cas aktivity v UNIX sekundach
        $startDayTimestamp = strtotime($activityData['start_date_local']);
        //datum aktivity pro dotaz do DB
        $activityDate = date("Y-m-d", $startDayTimestamp);
        //pole pro ulozeni bodu trasy
        $trackPointArray = [];
        //vytvoreni noveho pole se stejnymi paramatry jak GPX soubor
        $activityDataArray = [];
        // vytvoreni pole ve stejne strukture jak GPX soubor

        /*
            proverit, zda tu delkau zavodu nekontrolovat uz tady at se zbytecne nemusi tvorit pole

        */


        foreach ($activityData['latlng']['data'] as $key => $val)
        {
            $activityDataArray[] = [
                    'latlng' => $val,
                    'time' => $activityData['time']['data'][$key] + $startDayTimestamp,
                    'distance' => $activityData['distance']['data'][$key],
                    'altitude' => $activityData['altitude']['data'][$key],
                    'cadence' => $activityData['cadence']['data'][$key],
                    'seconds' => $activityData['time']['data'][$key],
                ];
        }


        /* výpočet celkové vzdálenosti aktivity */
        $activityDistance = $this->activityDistanceCalculation($activityDataArray);



        /* kontrola, jestli v daném časovém období existuje nějaký závod */
        $events = Event::where('distance', '<=', $activityDistance)



        ->where('platform_id',env("PLATFORM_ID"))
                        ->where('date_end', '>=', $activityDate)
                        ->where('distance', '<=', $activityDistance)
                        ->orderBy('event_type_id','DESC')
                        ->orderBy('distance','DESC')
                        ->get(['id', 'distance','event_type_id','time']);



        /* zadny zavod neni */
        if (count($events) == 0)
        {
            Log::alert("Uzivatel $userId nahrál aktivitu, ale v daném časovém období a v patřičné délce neexistuje žádný závod.");

            exit();
        }




        /* procházení závodů, jestli délkově odpovídají a jestli je k nim uzivatel prihlasen */
        foreach ($events as $key => $event)
        {
            $registrationId = $registration->registrationExists($userId, $event['id'],NULL,NULL)->id;

            //kontrola, jestli je uzivatel k nemu prihlasen
            if (!is_null($registrationId))
            {
                $userRegisteredToSomeEvent = true;


                if($event['event_type_id'] == 2)
                {



                    foreach($activityDataArray as $activityData)
                    {
                        //vytvorime TrackPointArray pro ulozeni do DB
                        $trackPointArray[] = [
                            'latitude' => $activityData['latlng'][0],
                            'longitude' => $activityData['latlng'][1],
                            'time' => $activityData['time'],
                            'altitude' => $activityData['altitude'],
                            'user_id' => $userId,
                            'cadence' => $activityData['cadence'],

                        ];


                      //  $activityDataDistance = round($this->haversineGreatCircleDistance($lastPointLat, $lastPointLon, $currentPointLat, $currentPointLon), 1);

                        //$distance += $activityDataDistance;








                        //pokud je vzdálenost větší než délka závodu, tak se vypocita cas a dal se v cyklu, ktery prochazi polem, nepokracuje
                        if ($activityData['seconds'] >= $event['time'])
                        {


                            $timeNavic = $activityData['seconds'] - $event['time'];
                            $distanceCm = $activityData['distance'] * 100;
                            $cmZaSekundu = $distanceCm / $activityData['seconds'] ;
                            $cmNavic = $cmZaSekundu * $timeNavic;
                            $cmPoKorekci = $distanceCm - $cmNavic;
                            $metryPoKorekci = intval(round($cmPoKorekci / 100));









                                    return [
                                        //      'finish_time' => $finishTime['finish_time'],
                                          //    'finish_time_sec' => $finishTime['finish_time_sec'],
                                          'finish_distance_km' => round(floatval($metryPoKorekci / 1000),2),
                                          'finish_distance_mile' => round(floatval(($metryPoKorekci * 0.8) / 1000),2),
                      
                                             'pace_km' => $this->averageTimePerKm($activityData['distance'],$event['time']),
                                             'pace_mile' => $this->pacePerMile($activityData['distance'],$event['time']),
                                              'track_points' => $trackPointArray,
                                             'registration_id' => $registrationId,
                                              'finish_time_date' => $activityDate,
                                          ];


                        }
                    }






                }



                else
                {






                //prochazeni pole s daty aktivity
                foreach($activityDataArray as $activityData)
                {
                    //vytvorime TrackPointArray pro ulozeni do DB
                    $trackPointArray[] = [
                        'latitude' => $activityData['latlng'][0],
                        'longitude' => $activityData['latlng'][1],
                        'time' => $activityData['time'],
                        'altitude' => $activityData['altitude'],
                        'user_id' => $userId,
                        'cadence' => $activityData['cadence'],

                    ];







                    //pokud je vzdálenost větší než délka závodu, tak se vypocita cas a dal se v cyklu, ktery prochazi polem, nepokracuje
                    if($activityData['distance'] >= $event['distance'])
                    {
                        $finishTime = $this->finishTimeCalculation($event['distance'],$activityData['distance'],$activityData['time'],$startDayTimestamp);

                        return [
                            'finish_time' => $finishTime['finish_time'],
                            'finish_time_sec' => $finishTime['finish_time_sec'],
                            'pace' => $finishTime['pace'],
                            'track_points' => $trackPointArray,
                            'registration_id' => $registrationId,
                            'finish_time_date' => $activityDate,
                        ];
                    }
                }

            }

                break;
            }


        }








        if(!$userRegisteredToSomeEvent)
        {
            Log::alert('Uživatel '.$userId.' není prihlaseny k zadnemu zavodu v daném časovém období a odpovídající délce.');

            exit();

        }

    }



    private function webhookType2($activityDataArray,$event)
    {

    }












    /**
     * to je nahravani z autodistance upload
     */

    private function nonameYet($userId,$activityId)
    {

        $activityId = $this->removePossibleSlashBehindString($activityId);

        $user = User::select('id','strava_access_token','strava_refresh_token','strava_expires_at')->where('id',$userId)->first();


        if($user->strava_expires_at > time())
        {

            //$url = "https://www.strava.com/api/v3/activities/".$request->input('object_id')."?include_all_efforts=true";

            $url = "https://www.strava.com/api/v3/activities/".$activityId."/streams?keys=time,latlng,altitude&key_by_type=true";




            $token = $user->strava_access_token;
            $response = Http::withToken($token)->get($url)->json();



            if($response)
            {
                $url = "https://www.strava.com/api/v3/activities/".$activityId."?include_all_efforts=false";
                $response += Http::withToken($token)->get($url)->json();
               // dd($response);

                //$data = $this->dataProcessing($resultService,$registration,$trackPoint,$event,$response,$user->id);
            }

        }
        else
        {
            $response = Http::post('https://www.strava.com/oauth/token', [
                'client_id' => '117954',
                'client_secret' => 'a56df3b8bb06067ebe76c7d23af8ee8211d11381',
                'refresh_token' => $user->strava_refresh_token,
                'grant_type' => 'refresh_token',
            ]);

            $body = $response->body();
            $content = json_decode($body, true);


            $user1 = User::where('id',$user->id)->first();
            $user1->strava_access_token = $content['access_token'];
            $user1->strava_refresh_token = $content['refresh_token'];
            $user1->strava_expires_at = $content['expires_at'];


            $user1->save();



            $url = "https://www.strava.com/api/v3/activities/".$activityId."?include_all_efforts=true";
            $token = $user->strava_access_token;
            $response = Http::withToken($token)->get($url);
            $data = $content;

            $data = $user1;
            $data .= "tady jsem";



        }

    }





    public function overallDistance($request, $registration)
    {


        $trackPointArray = [];
        $file = $request->file('file');



        $xmlObject = simplexml_load_file(trim($file));



        $lastPointLat = null;
        $lastPointLon = null;
        $currentPointLat = null;
        $currentPointLon = null;
        $distance = 0;

        $originalDateTime = $xmlObject->metadata->time;


        $finishTimeDate = Carbon::parse($originalDateTime)->format('Y-m-d');
        //dd($activityDate);

        $events = Event::where('date_start', '<=', $finishTimeDate)
        ->where('date_end', '>=', $finishTimeDate)
        ->orderBy('distance', 'DESC')
        ->get(['id', 'distance']);

        if (!isset($event)) {
            //TODO dopsat vyjimku, ze neexistuje zadny zavod v urcenem casovem obdobi

        }



        // iteration through gpx
        $i = 1;
        foreach ($xmlObject->trk->trkseg->trkpt as $point) {
            //dump($point);

            $time = $this->iso8601ToTimestamp($point->time);

            if ($i == 1) {

                $startDayTimestamp = $time;
                // dd($startDayTimestamp);
            }





            $lastPointLat = $currentPointLat;
            $lastPointLon = $currentPointLon;
            $currentPointLat = floatval($point['lat']);
            $currentPointLon = floatval($point['lon']);







            if ($lastPointLat != null) {
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
                    'elevation' => $point->ele
                    ]
                ];



                $trackPointArray1[] = [
                    'latitude' => $currentPointLat,
                    'longitude' => $currentPointLon,
                    //'time' => $time,
                    //'elevation' => $point->ele,
                    'user_id' => $request->user()->id,
                ];

                $i++;
            }

            //dd();




            foreach ($events as $event) {
                if ($distance >= $event['distance']) {

                    if (isset($registration->registrationExists($event['id'], $request->user()->id)->id)) {
                        $registrationId = $registration->registrationExists($event['id'], $request->user()->id)->id;
                        //dd( $registration_id );






                        $this->eventDistance = $event['distance'];

                        foreach ($trackPointArray as $trackPoint) {
                            if ($trackPoint['distance'] >= $event['distance']) {

                                $finishTime = $this->finishTimeCalculation($trackPoint['time'], $trackPoint['distance'], $startDayTimestamp);
                                break;
                            }
                        }


                        //dd($finishTime);



                        return [
                            'finish_time' => $finishTime['finish_time'],
                            'finish_time_sec' => $finishTime['finish_time_sec'],
                            'pace' => $finishTime['pace'],
                            'track_points' => $trackPointArray1,
                            'registration_id' => $registrationId,
                            'finish_time_date' => $finishTimeDate,
                        ];


                    } else {
                        //dd('neni');

                        Log::info('Event ' . $event['id'] . ' délkově odpovídá, ale uživatel id ' . $request->user()->id . ' k nemu není přihlášený');
                        continue;
                    }

                    break;


                }

            }


        }











    /**
     * vyextrahuje id aktivity z odkazu na strave
    */

    public function getActivityIdFromStravaShareLink($shareLink)
    {

        $lastChar = substr($shareLink, -1);
        if($lastChar == '/')
        {
            $shareLink = substr($shareLink, 0, -1);
        }

        $container = [];
        $history = Middleware::history($container);

        $stack = HandlerStack::create();
        $stack->push($history);

        $client = new Client([
            'handler' => $stack,

            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
            ]
        ]);

        $client->get($shareLink);

        foreach ($container as $transaction) {
            $finalUrl = (string)$transaction['request']->getUri();
        }

        if (preg_match('/\/activities\/(\d+)/', $finalUrl, $matches)) {
            $activityId = $matches[1];
            return $activityId;
        }
        else {
            return false;
        }
}












    private function finishTimeCalculation($eventDistance, $rawActivityDistance, $rawDayTimestamp, $startDayTimestamp = null)
    {
        if ($startDayTimestamp == null)
        {
            $rawFinishTimeSec = $rawDayTimestamp;
        }
        else {
            $t = new Carbon($rawDayTimestamp);

            $finishDayTimestamp = $t->timestamp;

            $rawFinishTimeSec = $finishDayTimestamp - $startDayTimestamp;
        }

        $finishTime = $this->finishTimeRecountAccordingDistance($eventDistance, $rawActivityDistance, $rawFinishTimeSec);

        
        
        
        return [
            'finish_time' => $finishTime['finish_time'],

            'finish_time_sec' => intval(round($finishTime['finish_time_sec'], 0)),

            'pace' => $this->averageTimePerKm($eventDistance,$finishTime['finish_time_sec'])
        ];

    }


    private function finishTimeRecountAccordingDistance($eventDistance, $activityDistance, $rawFinishTimeSec)
    {
        //kolik sekund trva 1 metr
        $secPerMeter = $rawFinishTimeSec / $activityDistance;

        $plusDistance = $activityDistance - $eventDistance;

        $plusSecond = $plusDistance * $secPerMeter;

        $finishTimeSec = intval(round($rawFinishTimeSec - $plusSecond));


        $finishTime = Carbon::createFromTimestamp($finishTimeSec)->format('G:i:s');

        return [
            "finish_time" => $finishTime,

            "finish_time_sec" => $finishTimeSec
        ];

    }


    private function activityDistanceCalculation($activityDataArray)
    {
        $lastPointLat = null;
        $lastPointLon = null;
        $currentPointLat = null;
        $currentPointLon = null;
        $distance = 0;


        foreach ($activityDataArray as $point) {
            $lastPointLat = $currentPointLat;
            $lastPointLon = $currentPointLon;
            $currentPointLat = floatval($point['latlng'][0]);
            $currentPointLon = floatval($point['latlng'][1]);

            if ($lastPointLat != null) {
                $pointDistance = round($this->haversineGreatCircleDistance($lastPointLat, $lastPointLon, $currentPointLat, $currentPointLon), 1);
                $distance += $pointDistance;
            }
        }

        return $distance;
    }


        private function averageTimePerKm($eventDistance,$finishTimeSec)
        {
            $secondPerKm = round(($finishTimeSec * 1000) / $eventDistance);

            $timeObj = Carbon::createFromTime(0, 0, 0)->addSeconds($secondPerKm);

            return substr($timeObj->format('i:s'), 1);
        }

        function isTimeInRange($time, $dateEventStartTimestamp, $dateEventEndTimestamp)
        {
            if ($time >= $dateEventStartTimestamp && $time <= $dateEventEndTimestamp) {
                return true;
            } else {
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



        private function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
        {

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

            $randomNumber1 = rand($min, $max);
            $randomNumber2 = rand($min, $max);

            if ($randomNumber1 == $randomNumber2) {
                $this->generateRandomNumbers();
            } else {
                $randomNumbers = [$randomNumber1, $randomNumber2];
                sort($randomNumbers);

                return $randomNumbers;
            }
        }


        public function resultSave($request,$registrationId,$finishTime)
        {
            $result = new Result();

            $result->registration_id = $registrationId;

            $result->finish_time_date = $finishTime['finish_time_date'];

            $result->pace_km = $finishTime['pace'];


            if(isset($finishTime['finish_time']))
            {
                $result->finish_time = $finishTime['finish_time'];
            }

            if(isset($finishTime['finish_time_sec']))
            {
                $result->finish_time_sec = $finishTime['finish_time_sec'];
            }

            if(isset($finishTime['finish_distance_km']))
            {
                $result->finish_distance_km = $finishTime['finish_distance_km'];
            }

            if(isset($finishTime['finish_distance_mile']))
            {
                $result->finish_distance_mile = $finishTime['finish_distance_mile'];
            }

            if(isset($finishTime['pace_mile']))
            {
                $result->pace_mile = $finishTime['pace_mile'];
            }


            DB::beginTransaction();

            try
            {
                $result->save();
            }
            catch(QueryException $e)
            {

                return [
                    'error' => 'ERROR_DB',

                    'error_message' => $e->getMessage(),

                ];
            }

            for($i = 0; $i < count($finishTime['track_points']); $i++)
            {
                $finishTime['track_points'][$i]['result_id'] = $result->id;
            }

            $trackPoint = new TrackPoint();


            try{
                $trackPoint::insert($finishTime['track_points']);

                DB::commit();
            }
            catch (UniqueConstraintViolationException $e)
            {
                if($e->errorInfo[1] == 1062)
                {
                    DB::rollback();

                    throw new DuplicityTimeException();
                }
            }

            $r = Result::where('registration_id', $registrationId)
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

            $event = new Event();

            return [
                'results' =>  Result::selectRaw('id,DATE_FORMAT(finish_time_date,"%e.%c") AS date,finish_time,pace_km')
                ->where('registration_id', $registrationId)
                ->orderBy('finish_time', 'asc')
                ->get(),
                'event' => $event::find($request->eventId),
                'last_id' => $lastId,
                'rank' => $rank

            ];


        }


    public function getSubdomain($url)
    {
        $parseUrl = parse_url($url);

        $explodeHost = explode('.', $parseUrl['host']);

        return $explodeHost[0];
    }

    public function getActivityId($string)
    {
        $lastChar = substr($string, -1);
        if($lastChar == '/')
        {
            $string = substr($string, 0, -1);
        }


        return substr($string, strrpos($string, '/') + 1);
    }









    }
