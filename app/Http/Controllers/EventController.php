<?php

namespace App\Http\Controllers;

use App\Helper\Helpers;
use App\Models\Event;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     *
     */

    // public function search(Request $request)
    // {
    //     $search = $request->input('search');

    //     $events = Event::query()
    //         ->where('title', 'LIKE', "%{$search}%")
    //         ->orWhere('description', 'LIKE', "%{$search}%")
    //         ->get();
    //     return view('search', compact('events'));
    // }
    
    public function index()
    {
        //$events = Event::all();
        $events = Event::all()->where('status', 'confirmed');
        //dd($events);
        return view('event.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('event.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $startDate = "";
        // $endDate = "";
        // $isRecurring = false;
        // $recurrenceRule = NULL;
        // if($request->input('recurringCheck') == 'yes')
        // {
        //     $isRecurring = true;
        //     switch ($request->RecurringOptions) {
        //         case 'daily':
        //             $recurrenceRule = "RRULE:FREQ=DAILY;INTERVAL=1";
        //             break;

        //         case 'weekday':
        //             $recurrenceRule = "RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=MO,TU,WE,TH,FR";
        //             break;
                
        //         case 'custom':
        //             dd('it should be custom');
        //             break;
        //         default:
        //             break;
        //     }
        // }
        // if($request->input('allday') == 'yes')
        // {
        //     $startDate = array('date' => $request->input('alldaystartdate'));
        //     $endDate = array('date' => $request->input('alldayenddate'));
        // }
        // else
        // {
        //     $startDate = array('dateTime' => date("c", strtotime($request->input('eventstart')) - 60 * 60 * 5.5));        
        //     $endDate = array('dateTime' => date("c", strtotime($request->input('eventend')) - 60 * 60 * 5.5));        
        // }
        // Helpers::insertNewEvent(
        //     session('token'),
        //     $request->input('eventname'), 
        //     $request->input('eventdesc'),
        //     $startDate,
        //     $endDate,
        //     $request->input('eventlocation'), 
        //     $request->input('eventattendee'),
        //     $request->input('meetinglink'),
        //     $isRecurring,
        //     $recurrenceRule
        // );

        //dump($request->input('eventstart'));
        //dd();
        //dd(substr('ANURAG', 0, -2));
        //dd(substr_replace("ABCD", "EFGH", 2));
        //$requestData = $request->all();
        $newEventUrl = 'https://www.googleapis.com/calendar/v3/calendars/'. Auth::user()->email .'/events?conferenceDataVersion=0&sendUpdates=all';
        $curlPostArray = array();
        if($request->input('eventname') != NULL)
        {
            $curlPostArray = array_merge($curlPostArray, array("summary" => $request->input('eventname')));
        }
        if($request->input('eventdesc') != NULL)
        {
            $curlPostArray = array_merge($curlPostArray, array("description" => $request->input('eventdesc')));
        }
        if($request->input('recurringCheck') == 'yes')
        {
            $isRecurring = true;
            switch ($request->RecurringOptions) {
                case 'daily':
                    $eventStart = $request->input('eventstart');
                    $eventEnd = $request->input('eventend');
                    $onlyEventStartDate = substr($eventStart, 0, -6);
                    //dump($eventStart);
                    //dump($eventEnd);
                    // dump($request->input('eventstart'));
                    // dump($request->input('eventend'));
                    //strpos($eventStart, 'T');
                   // dump($onlyEventStartDate);
                    $position = strpos($eventStart, 'T');
                    $eventEnd = substr_replace($eventEnd, $onlyEventStartDate, 0, -6);
                    //dump($eventStart);
                    //dump($eventEnd);
                    //dd($eventEnd);
                    //dd($request->input('eventend'));
                    $request->merge([
                        'eventend' => $eventEnd
                    ]);
                    //$request->all('eventend') = $request->input('eventstart'); 
                    $curlPostArray = array_merge($curlPostArray, array("recurrence" => array("RRULE:FREQ=DAILY;INTERVAL=1")));
                    break;

                case 'weekday':
                    $curlPostArray = array_merge($curlPostArray, array("recurrence" => array("RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=MO,TU,WE,TH,FR")));
                    break;
                
                // case 'custom':
                //     dd('it should be custom');
                //     break;
                default:
                    break;
            }
        }
        if($request->input('allday') == "yes")
        {
            $curlPostArray = array_merge($curlPostArray, array("start" => array('date' => $request->input('alldaystartdate'))));
            $curlPostArray = array_merge($curlPostArray, array("end" => array('date' => $request->input('alldayenddate'))));
        }
        else
        {
            $curlPostArray = array_merge($curlPostArray, array("start" => array('dateTime' => date("c", strtotime($request->input('eventstart')) - 60 * 60 * 5.5))));
            $curlPostArray = array_merge($curlPostArray, array("end" => array('dateTime' => date("c", strtotime($request->input('eventend')) - 60 * 60 * 5.5))));
        }
        
        if($request->input('eventlocation') != NULL)
        {
            $curlPostArray = array_merge($curlPostArray, array("location" => $request->input('eventlocation')));
        }
        $attendees = $request->input('eventattendee'); 
        if(strpos($attendees, ',') !== false)
        {
            $emails = explode(",", $attendees);
            foreach ($emails as $key => $emailId) 
            {
                $emailNew[] = ['email'=> $emailId];            
            }
            $curlPostArray = array_merge($curlPostArray, array("attendees" => $emailNew));
        }
        else
        {
            $curlPostArray = array_merge($curlPostArray, array("attendees" =>  array(array('email' => $attendees))));
        }
        if($request->input('meetinglink') == 'yes')
        {
            $newEventUrl = 'https://www.googleapis.com/calendar/v3/calendars/'. Auth::user()->email .'/events?conferenceDataVersion=1&sendUpdates=all';
            $curlPostArray = array_merge($curlPostArray, array("conferenceData" => array('createRequest' => array('conferenceSolutionKey' => array('type' => 'hangoutsMeet'), 'requestId' => uniqid()))));
        }
        //dd($curlPostArray);
        Helpers::insertNewEvent($curlPostArray, $newEventUrl);
        return redirect()->route('event.index');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('event.show', ['event' => Event::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
