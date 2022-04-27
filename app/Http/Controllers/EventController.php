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
     */
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
        //dd($request->input('eventattendee'));
        // $attendeesArray = array();
        // if(strpos($request->input('eventattendee'), ',') !== false)
        // {
        //     $attendeesArray = explode(",", $request->input('eventattendee'));
        // }
        // $attendeesEmails = array();
        // $totalAttendees = count($attendeesArray);
        // $attendees = array('attendees');
        // for($attendeeEmail = 0; $attendeeEmail < $totalAttendees; $attendeeEmail++)
        // {
        //     dump(json_encode(array('email' => $attendeesArray[$attendeeEmail])));
        // }
        // dd($attendeesEmails);

        //dd(date("c", strtotime($request->input('eventend')).date_default_timezone_set('Asia/Kolkata')));
            Helpers::insertNewEvent(
                session('token'),
                $request->input('eventname'), 
                $request->input('eventdesc'), date("c", strtotime($request->input('eventstart')) - 60 * 60 * 5.5),
                date("c", strtotime($request->input('eventend')) - 60 * 60 * 5.5), $request->input('eventlocation'), 
                $request->input('eventattendee'),
                $request->input('meetinglink')
            );
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
