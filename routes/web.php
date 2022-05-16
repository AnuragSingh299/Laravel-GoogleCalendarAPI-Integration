<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\GoogleUser;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Helper\Helpers;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;
use Carbon\Carbon as CarbonCarbon;
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
    $scope = 'https://www.googleapis.com/auth/calendar';
    $userExists = Helpers::checkUserExists($social_user->email);
    if(!$userExists)
    {
        return redirect('/login')->with(['message' => 'User does not exist']);
    }
    //dd($social_user);
    //dd($social_user->approvedScopes);
    if(in_array($scope,$social_user->approvedScopes)) 
    {
        $no_of_users = DB::table('google_users')
                ->where('email', $social_user->email)
                ->where('user_id', Auth::id())
                ->count();
        if($no_of_users < 1)
        {
           DB::table('google_users')->insert([
               'id' => Str::uuid()->toString(),
               'google_id' => $social_user->id,
               'user_id' => Auth::id(),
               'email' => $social_user->email,
               'access_token' => $social_user->token,
               'refresh_token' => $social_user->refreshToken,
               'expiry_at' => Carbon::now(new DateTimeZone('Asia/Kolkata'))->addHour(1)->toDateTimeString(),
           ]);
        }
        else
        {
            $expiryAt = Helpers::getUserAccessTokenExpiry(Auth::id());
            //if token is expired
            if(Helpers::isTokenExpired($expiryAt))
            {
                $userRefreshToken = Helpers::getUserRefreshToken(Auth:: id());
                $newAccessToken = Helpers::generateAccessToken($userRefreshToken);
                $newExpiryAt = Carbon::now(new DateTimeZone('Asia/Kolkata'))->addHour(1)->toDateTimeString();
                Helpers::storeNewAccessToken(Auth::id(), $newExpiryAt, $newAccessToken);
                //Helpers::getAllEvents($newAccessToken, "anuragsingh22324@gmail.com");
                Helpers::refreshDatabase($newAccessToken, "anuragsingh22324@gmail.com");
                //Helpers::createNewCalendar($newAccessToken, "skggjldg", "sdfsdafsdf");
                session(['token' => $newAccessToken]);
                return redirect()->back();
            }
            else
            {
                $oldAccessToken = Helpers::getUserAccessToken(Auth::id());    
                //Helpers::getAllEvents($oldAccessToken, "anuragsingh22324@gmail.com");//controller here  
                Helpers::refreshDatabase($oldAccessToken, "anuragsingh22324@gmail.com");
                //Helpers::createNewCalendar($oldAccessToken, "skggjldg", "sdfsdafsdf");   
                session(['token' => $oldAccessToken]);
                return redirect()->back();   
            }
        }
    }
    else
    { 
        return view('home');
    }
});

Auth::routes(['register' => false]);

//Route::get('event/search', 'EventController@search');
Route::resource('calendar', CalendarController::class)->middleware('auth');
Route::resource('event', EventController::class)->middleware('auth');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('calendars', function(){
    //$token = Helpers::updateAccessToken();
    Helpers::refreshDatabase();
    return redirect()->route('calendar.index');
})->name('syncCalendar');

Route::get('events', function(){
    //$token = Helpers::updateAccessToken();
    Helpers::refreshDatabase(Auth::user()->email);
    return redirect()->route('event.index');
})->name('syncEvent');