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

        $serieId = 2;

        $registrationSerieExists = $registration->registrationExists($userId, $serieId);

      //  dd($registrationSerieExists);

        if ($registrationSerieExists->isEmpty()) {

            dd('budete muset zaplatit');
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
}
