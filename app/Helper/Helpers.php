<?php

namespace App\Helper;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class Helpers {
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

    public static function authorize()
    {
        $end_point = 'https://accounts.google.com/o/oauth2/v2/auth';
        $client_id = config('services.google.client_id');
        $client_secret = config('services.google.client_secret');
        $redirect_uri = 'http://127.0.0.1:8000/';
        $scope = 'https://www.googleapis.com/auth/calendar';


        $authUrl = $end_point.'?'.http_build_query([
            'client_id'              => $client_id,
            'redirect_uri'           => $redirect_uri,              
            'scope'                  => $scope,
            'access_type'            => 'offline',
            'include_granted_scopes' => 'true',
            'state'                  => 'state_parameter_passthrough_value',
            'response_type'          => 'code',
        ]);

        echo '<a href = "'.$authUrl.'">Authorize</a></br>';

        //dd($authUrl);
        // Generate new Access Token and Refresh Token if token.json doesn't exist
        if ( !file_exists('token.json') ){
            
            if ( isset($_GET['code'])){
                $code = $_GET['code'];         // Visit $authUrl and get the authentication code
            }else{
                return;
            } 
            //dd($code);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://accounts.google.com/o/oauth2/token");
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [ 'Content-Type: application/x-www-form-urlencoded']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'code'          => $code,
                'client_id'     => $client_id,
                'client_secret' => $client_secret,
                'redirect_uri'  => $redirect_uri,
                'grant_type'    => 'authorization_code',
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close ($ch);
            
            file_put_contents('token.json', $response);
            $response = file_get_contents('token.json');
            $array = json_decode($response);
            $access_token = $array->access_token;
            return array($authUrl, $access_token);
        }
        else{
            $response = file_get_contents('token.json');
            $array = json_decode($response);
            //dd($response);
            $access_token = $array->access_token;
            $refresh_token = $array->refresh_token;
            return array($authUrl, $access_token);
            // Check if the access token already expired
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token='.$access_token); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $error_response = curl_exec($ch);
            $array = json_decode($error_response);
            

            if( isset($array->error)){
                
                // Generate new Access Token using old Refresh Token
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,"https://accounts.google.com/o/oauth2/token");
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'client_id'     => $client_id,
                    'client_secret' => $client_secret,
                    'refresh_token'  => $refresh_token,
                    'grant_type'    => 'refresh_token',
                ]));
                
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close ($ch);
                file_put_contents('token.json', $response);
                $response = file_get_contents('token.json');
                $array = json_decode($response);
                $access_token = $array->access_token;
                return array($authUrl, $access_token);
            }  
        }

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
        //dd($data);
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

    public static function callApi($access_token, $url)
    {
        $api_url = $url;           
    }
}
