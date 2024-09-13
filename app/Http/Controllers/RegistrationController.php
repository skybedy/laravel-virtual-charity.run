<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Registration;
use App\Models\Event;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class RegistrationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Category $category)
    {
        return view('registrations.index', [
            'categories' => $category->categoryListAbsolute($request->eventId),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     * It was used for the old registration system and for each event extra
     */
    public function createOld(Request $request, Category $category, Registration $registration)
    {

        $eventId = $request->eventId;
       // dd($eventId);

        $userId = $request->user()->id;

        $serieId = 2;

        $registrationSerieExists = $registration->registrationExists($userId, $eventId, $serieId);


        if ($registrationSerieExists->isEmpty()) {

            return view('registrations.payment', [
                'eventId' => $eventId,
            ]);
        }
        else
        {

            $registrationEventExists = $registrationSerieExists->firstWhere('event_id', $eventId);

            if($registrationEventExists)
            {
                session()->flash('info', 'Na tento závod už je registrace provedená');
            }
            else
            {
                $this->store($request,$registration,$category);
            }

            return redirect()->back();
        }

    }


    public function create(Request $request, Category $category, Registration $registration)
    {
      //  dd("tu");
        //$eventId = $request->eventId;
        //dd($eventId);

        $userId = $request->user()->id;



        $registrationSerieExists = $registration->registrationExists($userId, env('ACTIVE_SERIE_ID'));


        if ($registrationSerieExists->isEmpty()) {

            return view('registrations.payment', [
                'eventId' => 1,
            ]);
        }
        else
        {

            $registrationEventExists = $registrationSerieExists->firstWhere('event_id', $eventId);

            if($registrationEventExists)
            {
                session()->flash('info', 'Na tento závod už je registrace provedená');
            }
            else
            {
                $this->store($request,$registration,category: $category);
            }

            return redirect()->back();
        }

    }




    public function store(Request $request,Registration $registration, Category $category)
    {

        $events = Event::where(['platform_id' => env("PLATFORM_ID"),'serie_id' => env("ACTIVE_SERIE_ID")])->pluck('id');
        dd($events);

        $registration->create([
            'event_id' => $request->eventId,
            'user_id' =>   $request->user()->id,
            'category_id' => $category->categoryChoice($request->user()->gender, calculate_age($request->user()->birth_year))->id,
        ]);

        session()->flash('success', 'Byli jste úspěšně zaregistrováni');

        return redirect()->route('index');
    }


    public function checkout(Request $request,StripeClient $stripe)
    {

        $event_id = $request->eventId;

        // Vytvoření Stripe Checkout Session
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
                //'price' => 'price_1Ps1uS2LSxhftJEav9dO6DNQ', // testovací Price ID
                'price' => env('STRIPE_PRICE_ID'), // Production Price ID
                'quantity' => 1,
            ]],
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'success_url' => route('payment.success',$event_id),
            'cancel_url' => route('payment.cancel'),
            'automatic_tax' => [
                'enabled' => true,
            ],
            'payment_intent_data' => [
                'transfer_data' => ['destination' => env('STRIPE_CONNECT_CLIENT_ID')],
            ],
        ]);

        // Přesměrování na Stripe Checkout
        return redirect($checkout_session->url);

        }


    public function cancel()
    {
        dd('cancel');
    }


}
