<?php

use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('auth/google', function(){
    return Socialite::driver('google')->redirect();
})->name('loginGoogle');
Route::get('auth/google/callback', function(){
    try {
        $google_user = \Laravel\Socialite\Facades\Socialite::driver('google')->user();
        $user = \App\Models\Employee::where('email', $google_user->email)->first();
    
        if(!$user){
            $new_user = \App\Models\Employee::create([
                'name' => $google_user->getName(),
                'email' => $google_user->getEmail(),
                'image' => $google_user->getAvatar(),
                'google_id' => $google_user->getId(),
                'password' => \bcrypt('password'), // provide a default password value
                'phone' => 'null',
                'is_admin' => 0,
            ]);
    
            // Generate JWT token for new user
            $token = JWTAuth::attempt([
                'email' => $new_user->email,
                'password' => 'password',
            ]);
            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ], 200);
    
            //return \redirect()->intended('home')->withCookie(cookie('jwt_token', $token));
        } else {
            // Generate JWT token for existing user
            $token = JWTAuth::attempt([
                'email' => $user->email,
                'password' => 'password',
            ]);
            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ], 200);
        }
    } catch (\Throwable $th) {
        \dd('Something went wrong!'. $th->getMessage());
    }
})->name('callbackGoogle');
// Route::get('auth/google', [GoogleAuthController::class], 'redirect')->name('loginGoogle');
// Route::get('auth/google/callback', [GoogleAuthController::class], 'callbackGoogle')->name('callbackGoogle');
Route::get('/home', function(){
    return view('home'); 
})->name('home');
