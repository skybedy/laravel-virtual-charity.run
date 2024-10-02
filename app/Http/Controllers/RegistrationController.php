<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Registration;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use App\Models\PaymentRecepient;
use App\Models\Payment;

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
    public function create(Request $request, Category $category, Registration $registration, PaymentRecepient $paymentRecepient)
    {

        $event_id = $request->eventId;

        $user_id = $request->user()->id;

        if (!$registration->someRegistrationExists($user_id, $event_id))
        {

if($event_id < 5){
    return view('registrations.payment_znesnaze', [
        'payment_recepients' => $paymentRecepient->All(),
        'eventId' => $event_id,
    ]);
}
else
{
    return view('registrations.payment', [
        'payment_recepients' => $paymentRecepient->All(),
        'event_id' => $event_id,
    ]);
}


        }

        else
        {

            if($registration->eventRegistrationExists($user_id, $event_id))
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
            'user_id' =>   $request->user()->id,
            'category_id' => $category->categoryChoice($request->user()->gender, calculate_age($request->user()->birth_year))->id,
            'ids' => $registration->startNumber($request->eventId,$request->user()->id),
        ]);

        session()->flash('success', 'Byli jste úspěšně zaregistrováni');

        return redirect()->route('index');
    }




    public function checkoutDifferentPaymentRecipient(Request $request,StripeClient $stripe,PaymentRecepient $paymentRecepient)
    {



        //return $this->checkout($request,$stripe);



        $payment_recepient = $paymentRecepient->find($request->payment_recipient);




        $price =  $stripe->prices->retrieve($payment_recepient->stripe_price_id);

        // Vytvoření Stripe Checkout Session
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [[
                'price' => $payment_recepient->stripe_price_id, // Production Price ID
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
            'metadata' => [
                'amount' => $price->unit_amount, // Cena v v halerich
                'event_id' => $request->event_id,
                'payment_recipient_id' => $request->payment_recipient,
            ],

            'payment_intent_data' => [
                'transfer_data' => ['destination' => $payment_recepient->stripe_client_id],
                'setup_future_usage' => 'on_session', //mozna kvuli apple kdyz nebude fungovat,dat pryč
                'statement_descriptor' => 'VIRTUAL-CHARITY-RUN',
            ],
        ]);

        // Přesměrování na Stripe Checkout
        return redirect($checkout_session->url);
    }





        public function checkout($request,$stripe)
        {


            $price =  $stripe->prices->retrieve(env('STRIPE_PRICE_ID'));




            // Vytvoření Stripe Checkout Session
            $checkout_session = $stripe->checkout->sessions->create([
                'line_items' => [[
                    //'price' => 'price_1Ps1uS2LSxhftJEav9dO6DNQ', // testovací Price ID
                    'price' => env('STRIPE_PRICE_ID'), // Production Price ID
                    'quantity' => 1,
                ]],

                'mode' => 'payment',
                'success_url' => route('payment.success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel'),
                'metadata' => [
                    'amount' => $price->unit_amount, // Cena v v halerich
                    'event_id' => $request->event_id,
                    'payment_recipient_id' => $request->payment_recipient,
                ],

                'payment_intent_data' => [
                    'transfer_data' => ['destination' => env('STRIPE_CONNECT_CLIENT_ID')],
                    'setup_future_usage' => 'on_session', //mozna kvuli apple kdyz nebude fungovat,dat pryč
                    'statement_descriptor' => 'CHARITY RUN',

                ],
            ]);

            // Přesměrování na Stripe Checkout
            return redirect($checkout_session->url);

            }

    public function success(Request $request,StripeClient $stripe)
    {

        $session_id = $session_id = $request->get('session_id');

        $checkout_session = $stripe->checkout->sessions->retrieve($session_id);

        $this->createPayment($request, $checkout_session, $stripe);

        return redirect()->route('registration.store', ['eventId' => $checkout_session->metadata->event_id]);

    }



    private function createPayment($request, $checkout_session, $stripe)
    {
        if (!Payment::where('stripe_session_id', $checkout_session->id)->exists()) {
                $payment = new Payment();
                $payment->user_id = $request->user()->id;
                $payment->event_id = $checkout_session->metadata->event_id; // pokud si uložíš metadata při vytvoření session
                $payment->payment_recipient_id = $checkout_session->metadata->payment_recipient_id;
                $payment->amount = $checkout_session->metadata->amount / 100; // Cena v korunách
                $payment->stripe_session_id = $checkout_session->id;
                $payment->created_at = now();
                $payment->updated_at = now();
                $payment->save();
        }
    }

    public function cancel()
    {
        return redirect()->route('index')->with('error', 'Platba byla zrušena');
    }




















}
