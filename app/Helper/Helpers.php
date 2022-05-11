<?php

namespace App\Helper;

use App\Models\Event;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use DateTimeZone;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Str;
use Nette\Utils\Json;
use phpDocumentor\Reflection\PseudoTypes\True_;

use function PHPUnit\Framework\isNull;

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

        $url_parameters['fields'] = 'items(id,summary,description,accessRole,timeZone,primary)';
        $url_parameters['minAccessRole'] = 'owner';

        $url_calendars = 'https://www.googleapis.com/calendar/v3/users/me/calendarList?'. http_build_query($url_parameters);

        $ch = curl_init();		
        curl_setopt($ch, CURLOPT_URL, $url_calendars);		
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));	
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);	
        $data = json_decode(curl_exec($ch), true);
        //dd($data);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
        if($http_code != 200) 
            throw new Exception('Error : Failed to get calendars list');
        return $data;
        //dd($data['items']);   
    }

    public static function getAllEvents($access_token, $calendar_id)
    {
        $url_parameters = array();

        $url_parameters['fields'] = 'items(id,summary,description, start, end, status, location, attendees, hangoutLink)';
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
        return $data;
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
}

    public static function insertNewEvent($access_token, $summary, $description, $eventStart = NULL,
                               $eventEnd, $location = NULL, $attendees = NULL, $meetinglink = NULL)
    {
        $url_new_event = "";
        $curlPost = "";
        //$attendeesArray = array();
        $emailNew = [];
        if($meetinglink == "yes")
        {
            $url_new_event = 'https://www.googleapis.com/calendar/v3/calendars/'. Auth::user()->email .'/events?conferenceDataVersion=1&sendUpdates=all';
            if(strpos($attendees, ',') !== false)
            {
                $emails = explode(",", $attendees);
                
                foreach ($emails as $key => $emailId) 
                {
                    $emailNew[] = ['email'=> $emailId] ;            
                }
                $curlPost = array(
                    "summary" => $summary, "description" => $description, "start" => array('dateTime' => $eventStart),
                    "end" => array('dateTime' => $eventEnd), "location" => $location,
                    "conferenceData" => array('createRequest' => array('conferenceSolutionKey' => array('type' => 'hangoutsMeet'), 'requestId' => uniqid())),
                    "attendees" => $emailNew  
                );
                //dd(json_encode($curlPost, true, JSON_PRETTY_PRINT));
            }
            else
            {
                $curlPost = array(
                    "summary" => $summary, "description" => $description, "start" => array('dateTime' => $eventStart),
                    "end" => array('dateTime' => $eventEnd), "location" => $location,
                    "conferenceData" => array('createRequest' => array('conferenceSolutionKey' => array('type' => 'hangoutsMeet'), 'requestId' => uniqid())),
                    "attendees" => array(array('email' => $attendees))  
                );
            }
        }
        else
        {
            $url_new_event = 'https://www.googleapis.com/calendar/v3/calendars/'. Auth::user()->email .'/events?conferenceDataVersion=0';
            $curlPost = array(
                "summary" => $summary, "description" => $description, "start" => array('dateTime' => $eventStart),
                "end" => array('dateTime' => $eventEnd), "location" => $location
            );
        }
        //dd($url_new_event);
        //dd($curlPost);
        $ch = curl_init();		
        curl_setopt($ch, CURLOPT_URL, $url_new_event);		
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));	
        //dd(json_encode($curlPost));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));
        //curl_exec($ch);
        $data = json_decode(curl_exec($ch), true, JSON_PRETTY_PRINT);
        //dd($data);
        $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
        if($http_code != 200) 
            throw new Exception('Error : Failed to create event');
    } 

    public static function refreshDatabase($accessToken, $calendar_id = NULL)
    {
        if($calendar_id != NULL)
        {
            $events = Helpers::getAllEvents($accessToken, $calendar_id);
            //dd($events);
            $columns = array('event_start', 'event_end', 'attendees_emails', 'meeting_link');
            $eventData = array();
            $eventData = array_fill_keys($columns, "");
            foreach ($events['items'] as $event)
            {
                $eventData['event_id'] = ($event['id'] ?? null);//if field does not exist in json response field, set it to null
                $eventData['status'] = ($event['status'] ?? null);
                $eventData['title'] = ($event['summary'] ?? null);
                $eventData['description'] = ($event['description'] ?? null);
                $eventData['location'] = ($event['location'] ?? null);
                //$eventData['event_start'] = "";
                //var_dump($eventData['start'] ?? null);
                if(array_key_exists('start', $event) && array_key_exists('end', $event))
                {
                    if(array_key_exists('date', $event['start']) && array_key_exists('date', $event['end']))
                    {
                        $eventData['event_start'] = ($event['start']['date'] ?? null);
                        $eventData['event_end'] = ($event['end']['date'] ?? null);
                    }
                    else
                    {
                        $eventData['event_start'] = ($event['start']['dateTime'] ?? null);
                        $eventData['event_end'] = ($event['end']['dateTime'] ?? null);   
                    }
                }
                
                if(array_key_exists('attendees', $event))
                {
                    $noOfAttendees = count($event['attendees']);
                    for($attendee = 0 ; $attendee < $noOfAttendees; $attendee++)
                    {
                        $eventData['attendees_emails'] = $event['attendees'][$attendee]['email'];
                    }
                }
                $eventData['meeting_link'] = ($event['hangoutLink'] ?? null);
               // dump($eventData);
                //event_id should be unique in the events table
                DB::table('events')->upsert([
                    'id' => Str::uuid()->toString(),
                    'user_id' => Auth::id(),
                    'event_id' => $eventData['event_id'],
                    'title' => $eventData['title'],
                    'description' => $eventData['description'],
                    'status' => $eventData['status'],
                    'location' => $eventData['location'],
                    'event_start' => $eventData['event_start'],
                    'event_end' => $eventData['event_end'],
                    'attendees_emails' => $eventData['attendees_emails'],
                    'meeting_link' => $eventData['meeting_link'],
                ],['event_id'], ['title', 'description', 'status', 'location', 'event_start', 'event_end', 'attendees_emails', 'meeting_link']);
            }
        }    

        if(isNull($calendar_id))
        {
            $calendars = Helpers::GetCalendarsList($accessToken);
            //dd($calendars);
            $columns = array('calendar_id', 'title', 'description', 'access_role', 'timezone', 'primary');
            $calendarData = array();
            $caledarData = array_fill_keys($columns, "");
            foreach ($calendars['items'] as $calendar)
            {
                $calendarData['calendar_id'] = ($calendar['id'] ?? null);//if field does not exist in json response field, set it to null
                $calendarData['title'] = ($calendar['summary'] ?? null);
                $calendarData['description'] = ($calendar['description'] ?? null);
                $calendarData['access_role'] = ($calendar['accessRole'] ?? null);
                $calendarData['timezone'] = ($calendar['timeZone'] ?? null);
                $calendarData['primary'] = ($calendar['primary'] ?? false);
                
               //dump($calendarData);
                //event_id should be unique in the events table
                DB::table('calendars')->upsert([
                    'id' => Str::uuid()->toString(),
                    'user_id' => Auth::id(),
                    'calendar_id' => $calendarData['calendar_id'],
                    'title' => $calendarData['title'],
                    'description' => $calendarData['description'],
                    'access_role' => $calendarData['access_role'],
                    'timezone' => $calendarData['timezone'],
                    'primary' => $calendarData['primary'],
                ],['calendar_id'], ['title', 'description', 'access_role', 'timezone', 'primary']);
            }
        }
    }

    
}
