@extends('layouts.app')

@section('content')

<div class="container" style="font-family:sans-serif">
    <a href="{{ route('event.create') }}" class="blade-link" >Add new Event</a><br><br>
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
                            <th>Event Name</th>
                            <th>Description</th>
                            <th>Start</th>
                            <th>End</th>
                        </tr>
                    <tbody>
                        @foreach ($events as $event)
                            <tr>
                                <td><a href="{{ route('event.show', $event->id) }}">{{ $event->title }}</a></td>
                                {{-- <td style="white-space: nowrap">{{ $event->dob }}</td> --}}
                                <td>{{ $event->description }}</td>
                                <td>{{ $event->event_start }}</td>
                                {{-- <td>{{ $event->address }}</td>     --}}
                                <td>{{ $event->event_end }}</td>
                    {{-- <a href="/auth/redirect/calendar">Your Calendar List</a> --}}
                        @endforeach
		        </tbody>
                </div>
            </div>
    </div>
</div>
{{-- <center>
<iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=1&bgcolor=%23ffffff&ctz=Asia%2FKolkata&showTitle=0&showDate=1&showPrint=0&showTabs=0&showTz=0&src=YW51cmFnc2luZ2gyMjMyNEBnbWFpbC5jb20&color=%23039BE5" style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
</center> --}}

@endsection
