<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialiteController extends Controller
{
    // Redireziona l'utente al provider (Google o Facebook)
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // Gestisce il callback dal provider
    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        // Cerca l'utente esistente nel database
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Se l'utente esiste, effettua il login
            Auth::login($user);
        } else {
            // Se non esiste, crea un nuovo utente e effettua il login
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt('randompassword'), // Puoi generare una password casuale o lasciare vuoto
                'provider_id' => $socialUser->getId(),
                'provider' => $provider,
            ]);

            Auth::login($user);
        }

        // Redirige l'utente alla dashboard o a una pagina specifica
        return redirect()->route('dashboard');
    }
}
