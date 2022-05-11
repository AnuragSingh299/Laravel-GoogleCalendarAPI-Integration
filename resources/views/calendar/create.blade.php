@extends('layouts.app')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
    select {
        width: 400px;
        height: 30px;
    }
</style>

</script>
    
@section('content')
<form action="{{ route('calendar.store') }}" method="POST" class="form-group" style="width: 75%; margin: auto">
    @csrf
    <table class="table table-borderless" style="font-size: 15px ; background: white ; color: black ; border:none ">
        <tr>
            <td><label for="calendarname">Calendar name:</label><br></td>
        </tr>
        <tr>
            <td><input class="form-control" style="background: white ; border: solid 1px" type="text" id="calendarname" name="calendarname" required><br></td>
        </tr>
        <tr>
            <td><label for="calendardesc">Description:</label><br></td>
        </tr>
        
        <tr>
            <td><textarea name="calendardesc" class="form-control" style="background: white ; border: solid 1px"" id="" cols="20" rows="5"></textarea></td>
        </tr>
        <tr>
            <td><input type="submit"  name="submit"></td>
            <td><a href="{{ route('calendar.index') }}">Back</a></td>
        </tr>
    </table>   
</form>

@endsection
