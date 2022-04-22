<?php

namespace App\Helper;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use DateTimeZone;
use Illuminate\Support\Carbon;
use PhpParser\Node\Expr\FuncCall;

class Helpers {

    public static function createGoogleUser()
    {

    }

    public static function storeNewAccessToken($userId, $newExpiryAt, $newAccesToken)
    {
        DB::table('google_users')
                ->where('user_id', $userId)
                ->update(array('expiry_at' => $newExpiryAt, "access_token" => $newAccesToken));
    }

    public static function getUserAccessToken($userId)
    {   
        $accessToken = DB::table('google_users')
                            ->select('access_token')
                            ->where('user_id', $userId)
                            ->value('access_token');
        return $accessToken;
    }

    public static function getUserRefreshToken($userId)
    {   
        $refreshToken = DB::table('google_users')
                            ->select('refresh_token')
                            ->where('user_id', $userId)
                            ->value('refresh_token');
        return $refreshToken;
    }

    public static function getUserAccessTokenExpiry($userId)
    {   
        $expiryAt = DB::table('google_users')
                            ->select('expiry_at')
                            ->where('user_id', $userId)
                            ->value('expiry_at');
        return $expiryAt;

    }

    public static function generateAccessToken($refresh_token)
    {
        $token_url = "https://oauth2.googleapis.com/token";
        $request_data = array(
            "client_id" => config('services.google.client_id'),
            "client_secret" => config('services.google.client_secret'),
            "refresh_token" => $refresh_token,
            "grant_type" => "refresh_token"
        );
        $ch = curl_init($token_url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/x-www-form-urlencoded"
        )); 

        $result = curl_exec($ch);
        return json_decode($result)->access_token;
    }

    public static function isTokenExpired($expiryTime)
    {
        $expiryTime = Carbon::parse($expiryTime)->setTimezone('Asia/Kolkata')->subRealHours(5.5);
        $currentTime = Carbon::now(new DateTimeZone('Asia/Kolkata'));
        return $currentTime->gt($expiryTime);
    }

    public static function checkUserExists($userMail)
    {
        $user = User::where('email', $userMail)->first();
        if ($user) 
        {
            return $user->exists();
        }
        else
            return false;
    }

    public static function GetCalendarsList($access_token) 
    {
        $url_parameters = array();

        $url_parameters['fields'] = 'items(id,summary,timeZone,accessRole)';
        $url_parameters['minAccessRole'] = 'owner';

        $url_calendars = 'https://www.googleapis.com/calendar/v3/users/me/calendarList?'. http_build_query($url_parameters);

        $ch = curl_init();		
        curl_setopt($ch, CURLOPT_URL, $url_calendars);		
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));	
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);	
        $data = json_decode(curl_exec($ch), true);
        dd($data);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
        if($http_code != 200) 
            throw new Exception('Error : Failed to get calendars list');

        dd($data['items']);   
    }

    public static function getAllEvents($access_token, $calendar_id)
    {
        $url_parameters = array();

        $url_parameters['fields'] = 'items(id,summary,description, start, end)';
        $url_parameters['minAccessRole'] = 'owner';

        $url_calendars = 'https://www.googleapis.com/calendar/v3/calendars/'. $calendar_id.'/events?'. http_build_query($url_parameters);

        $ch = curl_init();		
        curl_setopt($ch, CURLOPT_URL, $url_calendars);		
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));	
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);	
        $data = json_decode(curl_exec($ch), true);
        dd($data);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
        if($http_code != 200) 
            throw new Exception('Error : Failed to get events list');

        dd($data['items']); 
    }

    public static function createNewCalendar($access_token, $summary, $description)
    {
        $url_new_calendar = 'https://www.googleapis.com/calendar/v3/calendars/';
        $curlPost = array("summary" => $summary, "description" => $description);
        $ch = curl_init();		
        curl_setopt($ch, CURLOPT_URL, $url_new_calendar);		
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));	
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));
        $data = json_decode(curl_exec($ch), true);
        //dd($data);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
        if($http_code != 200) 
            throw new Exception('Error : Failed to create calendar');
        dd($data); 
    }    
}
