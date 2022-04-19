<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\GoogleUser;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Helper\Helpers;
//use App\Helper\Helpers;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', function () {
    return view('welcome');
    // list($url, $token) = Helpers::authorize();
    
    // //dd($token, $url);
    // //Helpers::GetCalendarsList($token);
    // Helpers::getAllEvents($token, 'anuragsingh22324@gmail.com');
    // //Helpers::createNewCalendar($token, 'My second new calendar', 'Blues Clues 2');
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')
        ->scopes(['https://www.googleapis.com/auth/calendar.events'])
        ->with(['access_type' => 'offline'])
        ->redirect();
});

Route::get('/auth/callback', function () {
    $social_user = Socialite::driver('google')->user();
    $userExists = Helpers::checkUserExists($social_user->email);
    if(!$userExists)
    {
        return redirect('/login')->with(['message' => 'User does not exist']);
    }
    else
    {
        $google_user = GoogleUser::whereGoogleId($social_user->id)->first();
        $user = ($google_user) ? $google_user->user: new User;
        //if first time sign in store data in user table
        if (!$google_user) {
            $user->name = $social_user->name;
            $user->email = $social_user->email;
            $user->password = bcrypt(Str::random(20));
            $user->save();
            $google_user = new GoogleUser;
            $google_user->google_id = $social_user->id;
        }
        //dd($user->id);
        //$google_user->user_id = $user->id;
        //$google_user->email = $social_user->email;
        //after sign in update following in the google user table
        $google_user->access_token = $social_user->token;
        $google_user->refresh_token = $social_user->refreshToken ?? $google_user->refreshToken;
        $google_user->expires = Carbon::now()->timestamp + $social_user->expiresIn;
        $user->googleUser()->save($google_user);
        Auth::login($user);
        return redirect('/todo');
    }
   // dd($social_user);
    
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
