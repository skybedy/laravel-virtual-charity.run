<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Registration;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Checkout\Session;
use Stripe\Stripe;

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
     */
    public function create(Request $request, Category $category, Registration $registration)
    {
        $eventId = $request->eventId;

        $userId = $request->user()->id;

        $platformId = env("PLATFORM_ID");

        $serieId = env('ACTIVE_SERIE_ID');

        $registrationSerieExists = $registration->registrationExists($userId, $eventId, $platformId,$serieId);

        if ($registrationSerieExists->isEmpty())
        {
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

    public function store(Request $request,Registration $registration, Category $category)
    {
        $registration->create([
            'event_id' => $request->eventId,
            'user_id' =>  $userId = $request->user()->id,
            'category_id' => $category->categoryChoice($request->user()->gender, calculate_age($request->user()->birth_year))->id,
        ]);

        session()->flash('success', 'Byli jste úspěšně zaregistrováni');

        return redirect()->route('index');
    }










        public function checkout(Request $request,StripeClient $stripe)
        {
            // Vytvoření Stripe Checkout Session
            $checkout_session = $stripe->checkout->sessions->create([
                'line_items' => [[
                    //'price' => 'price_1Ps1uS2LSxhftJEav9dO6DNQ', // testovací Price ID
                    'price' => env('STRIPE_PRICE_ID'), // Production Price ID
                    'quantity' => 1,
                ]],

                'mode' => 'payment',
                'success_url' => route('payment.success',$request->eventId),
                'cancel_url' => route('payment.cancel'),

                'payment_intent_data' => [
                    'transfer_data' => ['destination' => env('STRIPE_CONNECT_CLIENT_ID')],
                    'setup_future_usage' => 'on_session', //mozna kvuli apple kdyz nebude fungovat,dat pryč
                ],
            ]);

            // Přesměrování na Stripe Checkout
            return redirect($checkout_session->url);

            }

    public function success()
    {
        dd('success');
    }

    public function cancel()
    {
        return redirect()->route('index')->with('error', 'Platba byla zrušena');
    }




















}
