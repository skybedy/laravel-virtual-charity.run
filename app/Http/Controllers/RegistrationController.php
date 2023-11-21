<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Category;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,Category $category)
    {
        return view('registrations.index',[
            'categories' => $category->categoryListAbsolute($request->eventId)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request,Category $category,Registration $registration)
    {

        $eventId = $request->eventId;
        $userId = $request->user()->id;

       
       if(! $registration->registrationExists($eventId,$userId))
       {
            $registration->create([
                'event_id' => $eventId,
                'user_id' => $userId,
                'category_id' => $category->categoryChoice($request->user()->gender, calculate_age($request->user()->birth_year))->id
            ]);

            session()->flash('status', 'Přihláška byla úspěšně odeslána');
       }
       else
       {
            session()->flash('status', 'Na tento závod už jsi přihlášený');
       }

       return redirect()->route('event.index');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Registration $registration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Registration $registration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Registration $registration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Registration $registration)
    {
        //
    }
}
