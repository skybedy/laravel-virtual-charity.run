<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class RegisteredProviderUserController extends Controller
{
    /*
    * Display the registration view.
    */
    public function create(Request $request): View
    {

        $nameExplode = explode(' ', $request->name);

        return view('auth.register-socialite', [
            'email' => $request->email,
            'id' => $request->id,
            'firstname' => $nameExplode[0],
            'lastname' => $nameExplode[1],
            'provider' => $request->provider,
            'first_year' => date('Y') - 99,
            'last_year' => date('Y') - 18,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $providerNameId = false;
        switch ($request->provider_name) {
            case 'facebook':
                $providerNameId = 'facebook_id';
                break;
            case 'google':
                $providerNameId = 'google_id';
                break;
        }

        $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'team' => 'max:255',
            'gender' => 'required',
            'birth_year' => 'required',
            'email' => 'required|string|email|max:255|unique:'.User::class,
        ]);

        //dd($provider);
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'team' => $request->team,
            'gender' => $request->gender,
            'birth_year' => $request->birth_year,
            'email' => $request->email,
            'password' => Hash::make('password'),
            $providerNameId => $request->provider_id,
        ]);

        //$user = User::create($request->all());

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Create a new controller instance.
     */
    public function redirectToProvider(string $provider)
    {

        //dd($provider);
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleProviderCallback(string $provider, Request $request)
    {
        try {

            $user = Socialite::driver($provider)->user();

            $finduser = User::where($provider.'_id', $user->id)->first();

            if ($finduser) {

                Auth::login($finduser);

                return redirect()->intended('/');

            } else {
                //          dd($user);

                return redirect()->route('register-socialite', [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'provider' => $provider,
                ]);
                /*
                return Inertia::render('Auth/RegisterSocialite', [
                    'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
                    'status' => session('status'),
                ]);*/

                /*

                $newUser = User::updateOrCreate(['email' => $user->email],[
                        'name' => $user->name,
                        'facebook_id'=> $user->id,
                        'password' => encrypt('password')
                    ]);*/

                //Auth::login($newUser);

                //return redirect()->intended('dashboard');
            }

        } catch (Exception $e) {
            // dd($e->getMessage());
        }
    }
}
