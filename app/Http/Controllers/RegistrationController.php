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


    protected $stripe;

    public function __construct(StripeClient $stripe)
    {
        $this->stripe = $stripe;
    }




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



    public function checkoutx(StripeClient $stripe)
    {







        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost:8000';

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
              # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
              'price' => 'price_1PrcVW2LSxhftJEaFkT5JRFh',
              'quantity' => 1,
            ]],

            'mode' => 'payment',
            'success_url' => route('paymant.success'),
            'cancel_url' => route('paymant.cancel'),
            'automatic_tax' => [
              'enabled' => true,
            ],
            ]);

header("HTTP/1.1 303 See Other");
header("Location:  {$checkout_session->url}");
        }







public function checkout(Request $request)
{

   // $stripe = new \Stripe\StripeClient("sk_test_51PVCa82LSxhftJEam6p0Npc4iMggfZdpR6aeVDjmncI9nKQPxocVn2Am2F9uoXF2Q7cy4lr8DbQF6cUpO2Gkp8Qd00Yu5e5aN8");

    $event_id = $request->eventId;

    // Definujte svou doménu
    $YOUR_DOMAIN = env('APP_URL'); // nebo 'http://localhost:8000'

    // Vytvoření Stripe Checkout Session
    $checkout_session = $this->stripe->checkout->sessions->create([
        'line_items' => [[
            'price' => 'price_1Ps1uS2LSxhftJEav9dO6DNQ', // Nahraďte svým Price ID
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
            'transfer_data' => ['destination' => 'acct_1PsJsP09SrMQLpVO'],
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
dd('cancel');
    }






    public function checkoutxxxx()
    {


            $product = $this->stripe->products->create([
                'name' => 'Startovne Virtual Charity Run',
                'description' => 'Startovne',
              ]);
              dump("Super, startovne ID =  {$product->id}");

              $price = $this->stripe->prices->create([
                'unit_amount' => 111,
                'currency' => 'czk',
                'recurring' => ['interval' => 'month'],
                'product' => $product['id'],
              ]);
             dd("ID platby je {$price->id}");


        }















}
