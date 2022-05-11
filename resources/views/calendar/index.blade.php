@extends('layouts.app')
@php
    use App\Helper\Helpers;
@endphp
@section('content')
{{-- @php
    Helpers::refreshDatabase(session('token'));
@endphp --}}
    {{-- <a href="{{ route('calendar.create') }}">Create new calendar</a> --}}
    <div class="container" style="font-family:sans-serif">
        <a href="{{ route('calendar.create') }}" class="blade-link" >Add new calendar</a><br><br>
        <div class="row justify-content-center">
            <div class="card" style="border: none">
                    {{-- <div >
                       <center><b style="font-size: 20px">Your Events</b></center>
                    </div> --}}
    
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="table" style="font-size: 15px ; border:none">
                            <tr>
                                <th>Calendar Name</th>
                                <th>Description</th>
                            </tr>
                        <tbody>
                            @foreach ($calendars as $calendar)
                                <tr>
                                    <td><a href="{{ route('calendar.show', $calendar->id) }}">{{ $calendar->title }}</a></td>
                                    {{-- <td style="white-space: nowrap">{{ $event->dob }}</td> --}}
                                    <td>{{ $calendar->description }}</td>
                                    
                        {{-- <a href="/auth/redirect/calendar">Your Calendar List</a> --}}
                            @endforeach
                    </tbody>
                    </div>
                </div>
        </div>
    </div>
    
@endsection
