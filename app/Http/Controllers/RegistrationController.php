<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Registration;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

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

        $serieId = 2;

        $registrationSerieExists = $registration->registrationExists($userId, $serieId);

      //  dd($registrationSerieExists);

        if ($registrationSerieExists->isEmpty()) {

            return view('registrations.payment', [
                //'events' => $event->eventList($request->user()->id),
            ]);
        }
        else
        {

            $registrationEventExists = $registrationSerieExists->firstWhere('event_id', $eventId);

            if($registrationEventExists)
            {
                session()->flash('info', 'Na tento závod už jsme vás zaregistrovali');
            }
            else
            {
                $registration->create([
                    'event_id' => $eventId,
                    'user_id' => $userId,
                    'category_id' => $category->categoryChoice($request->user()->gender, calculate_age($request->user()->birth_year))->id,
                ]);

                session()->flash('success', 'Byli jste úspěšně zaregistrováni');
            }

            return redirect()->back();
        }

    }

    public function checkoutx()
    {


        $stripe = new \Stripe\StripeClient("sk_test_51PVCa82LSxhftJEam6p0Npc4iMggfZdpR6aeVDjmncI9nKQPxocVn2Am2F9uoXF2Q7cy4lr8DbQF6cUpO2Gkp8Qd00Yu5e5aN8");

       Stripe::setApiKey('sk_test_51PVCa82LSxhftJEam6p0Npc4iMggfZdpR6aeVDjmncI9nKQPxocVn2Am2F9uoXF2Q7cy4lr8DbQF6cUpO2Gkp8Qd00Yu5e5aN8');


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







public function checkout()
{
    // Nastavení Stripe tajného klíče
    Stripe::setApiKey('sk_test_51PVCa82LSxhftJEam6p0Npc4iMggfZdpR6aeVDjmncI9nKQPxocVn2Am2F9uoXF2Q7cy4lr8DbQF6cUpO2Gkp8Qd00Yu5e5aN8');

    // Definujte svou doménu
    $YOUR_DOMAIN = env('APP_URL'); // nebo 'http://localhost:8000'

    // Vytvoření Stripe Checkout Session
    $checkout_session = Session::create([
        'line_items' => [[
            'price' => 'price_1PrcVW2LSxhftJEaFkT5JRFh', // Nahraďte svým Price ID
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('payment.success'),
        'cancel_url' => route('payment.cancel'),
        'automatic_tax' => [
            'enabled' => true,
        ],
    ]);

    // Přesměrování na Stripe Checkout
    return redirect($checkout_session->url);




















        /*
        $product = $stripe->products->create([
            'name' => 'Starter Subscription',
            'description' => '$12/Month subscription',
          ]);
          dump("Success! Here is your starter subscription product id: {$product->id}");

          $price = $stripe->prices->create([
            'unit_amount' => 1200,
            'currency' => 'usd',
            'recurring' => ['interval' => 'month'],
            'product' => $product['id'],
          ]);
         dd("Success! Here is your starter subscription price id: {$price->id}");*/


    }

    public function success()
    {
        dd('success');
    }

    public function cancel()
    {
dd('cancel');
    }
}
