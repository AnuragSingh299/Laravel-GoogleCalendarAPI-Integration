<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\GoogleUser;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Helper\Helpers;
use Illuminate\Support\Facades\DB;

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

Route::get('/', function() {
    return redirect()->route('login');
});

Route::get('/auth/redirect', function () {
    return Socialite::driver('google')
        ->with(['access_type' => 'offline', "prompt" => "consent select_account"])
        ->redirect();
});

Route::get('/auth/redirect/calendar', function () {
    return Socialite::driver('google')
        ->scopes(['https://www.googleapis.com/auth/calendar'])
        ->with(['access_type' => 'offline'])
        ->redirect();
})->name('calendar');

Route::get('/auth/callback', function () {
    $social_user = Socialite::driver('google')->user(); 
    $google_client_token = [
        'access_token' => $social_user->token,
        'refresh_token' => $social_user->refreshToken,
        'expires_in' => $social_user->expiresIn
    ];
    $scope = 'https://www.googleapis.com/auth/calendar';
    // $userExists = Helpers::checkUserExists($social_user->email);
    // if(!$userExists)
    // {
    //     return redirect('/login')->with(['message' => 'User does not exist']);
    // }
    //dd($social_user);
    //dd($social_user->approvedScopes);
    if(in_array($scope,$social_user->approvedScopes)) 
    {
        // $no_of_users = DB::table('google_users')
        //         ->where('email', $social_user->email)
        //         ->where('user_id', Auth::id())
        //         ->count();
        // if($no_of_users < 1)
        // {
        //    //check access token is expired or not
        // }
        // else
        // {
        //     //add data to google_users table
        // }
        //Helpers::generateAccessToken($google_client_token['refresh_token']);
        Helpers::GetCalendarsList($google_client_token['access_token']);
    }
    else
    { 
        return view('calendars.home');
    }
    
    //Helpers::GetCalendarsList($google_client_token['access_token']);
    //dd();
    
    //dd($google_client_token);
    
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
