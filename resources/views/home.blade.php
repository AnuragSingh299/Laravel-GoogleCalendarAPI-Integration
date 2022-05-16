@extends('layouts.app')
{{-- @php
    use App\Helper\Helpers;
@endphp --}}
@section('content')
    {{-- @php

        Helpers::updateAccessToken();
        //dd(session('token'));
        dd(Helpers::GetCalendarsList());
    @endphp --}}

<<<<<<< HEAD
=======
  <title>
    
  </title>


<link href='/docs/dist/demo-to-codepen.css' rel='stylesheet' />


  <style>

    html, body {
      margin: 0;
      padding: 0;
      font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
      font-size: 14px;
    }

    #calendar {
      max-width: 1100px;
      margin: 40px auto;
    }

  </style>



<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js'></script>

  



{{-- <script src='/docs/dist/demo-to-codepen.js'></script> --}}


  <script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,listYear'
      },

      displayEventTime: false, // don't show the time column in list view

      // THIS KEY WON'T WORK IN PRODUCTION!!!
      // To make your own Google API key, follow the directions here:
      // http://fullcalendar.io/docs/google_calendar/
      googleCalendarApiKey: 'key here',

     
      events: 'calendar I'd here',

      eventClick: function(arg) {

        // opens events in a popup window
        window.open(arg.event.url, '_blank', 'width=700,height=600');

        // prevents current tab from navigating
        arg.jsEvent.preventDefault();
      }

    });

    calendar.render();
  });

</script>

</head>
<body>
 

  <div id='calendar'></div>
</body>

</html>
>>>>>>> refs/remotes/origin/main

@endsection
