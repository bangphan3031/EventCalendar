<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
        try{
            $google_user = Socialite::driver('google')->user();
            $user = Employee::where('email', $google_user->email)->first();

            if(!$user){
                $new_user = Employee::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'image' => $user->avatar,
                    'google_id' => $user->id,
                    'is_admin' => 0,
                ]);

                Auth::login($new_user);

                return redirect()->intended('home');
            } else {

                Auth::login($user);
                return redirect()->intended('home');
            }
        } catch (\Throwable $th) {
            dd('Something went wrong!'. $th->getMessage());
        }

        $existingUser = Employee::where('email', $user->email)->first();
        if($existingUser){
            auth()->login($existingUser, true);
        } else {
            Employee::create([
                'name' => $user->name,
                'email' => $user->email,
                'image' => $user->avatar,
                'google_id' => $user->id,
                'is_admin' => 0,
            ]);
        }
        return redirect()->to('home');
    }

    // Redirect the user to the Google authentication page
    // public function redirect()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // Handle the Google authentication response
    // public function handleGoogleCallback()
    // {
    //     $user = Socialite::driver('google')->user();
        
    //     // Check if the user already exists in your database
    //     $existingUser = Employee::where('email', $user->getEmail())->first();
    //     if ($existingUser) {
    //         Auth::login($existingUser);
    //     } else {
    //         // Create a new user account
    //         $newUser = new Employee();
    //         $newUser->name = $user->getName();
    //         $newUser->email = $user->getEmail();
    //         $newUser->save();
            
    //         Auth::login($newUser);
    //     }
        
    //     return redirect()->intended('/');
    // }
}
