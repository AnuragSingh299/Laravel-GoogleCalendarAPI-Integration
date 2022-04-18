<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


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

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
