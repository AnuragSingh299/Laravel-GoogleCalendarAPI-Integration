@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="card" style="border: none ; width: 75%">
        <div class="card-body">
            <table class="table" style="font-size: 15px ; border:none">
                <tbody>
                    <tr>
                        <td>Name:</td>
                        <td>{{ $event->title }}</td>
                    </tr>
                    @if($event->description != NULL)
                    <tr>
                        <td>Description:</td>
                        <td>{{ $event->description }}</td>
                    </tr>
                    @endif
                    @if ($event->location != NULL)
                    <tr>
                        <td>Location:</td>
                        <td>{{ $event->location }}</td>
                    </tr>
                    @endif
                    @if ($event->event_start != NULL)
                    <tr>
                        <td>Event Start:</td>
                        <td>{{ $event->event_start }}</td>
                    </tr>
                    @endif
                    @if ($event->event_end != NULL)
                    <tr>
                        <td>Event End:</td>
                        <td>{{ $event->event_end }}</td>
                    </tr>
                    @endif
                    @if ($event->attendees_emails != NULL)
                    <tr>
                        <td>Attendees:</td>
                        <td>{{ $event->attendees_emails }}</td>
                    </tr>
                    @endif
                    @if ($event->meeting_link != NULL)
                    <tr>
                        <td>Meeting Link:</td>
                        <td><a href="{{ $event->meeting_link }}" title="Click to Join">{{ $event->meeting_link }}</a></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<div>
    <table>
      
    </table>
</div>
@endsection