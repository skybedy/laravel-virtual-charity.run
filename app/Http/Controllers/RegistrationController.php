<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Registration;
use Illuminate\Http\Request;

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

        if (! $registration->registrationExists($eventId, $userId)) {
            $registration->create([
                'event_id' => $eventId,
                'user_id' => $userId,
                'category_id' => $category->categoryChoice($request->user()->gender, calculate_age($request->user()->birth_year))->id,
            ]);

            session()->flash('status', 'Byli jste úspěšně zaregistrováni');
        } else {
            session()->flash('status', 'Na tento závod už jsme vás zaregistrovali');
        }

        return redirect()->back();

    }
}
