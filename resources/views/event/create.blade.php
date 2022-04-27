@extends('layouts.app')
@section('content')
<form action="{{ route('event.store') }}" method="POST" class="form-group" style="width: 75%; margin: auto">
    @csrf
    <table class="table" style="font-size: 15px ; background: white ; color: black ; border:none ">
        <tr>
            <td><label for="eventname">Event name:</label><br></td>
            <td><input class="form-control" style="background: white ; border: solid 1px" type="text" id="eventname" name="eventname" required><br></td>
        </tr>
        <tr>
            <td><label for="eventdesc">Event description:</label><br></td>
            <td><textarea name="eventdesc" class="form-control" style="background: white ; border: solid 1px"" id="" cols="20" rows="5"></textarea></td>
        </tr>
        <tr>
            <td> <label for="eventstart">Event Start:</label></td>
            <td><input type="datetime-local" class="form-control" style="background: white ; border: solid 1px"" value="data and time" id="eventstart" name="eventstart"></td>
        </tr>
        <tr>
            <td><label for="eventend">Event End:</label></td>
            <td><input type="datetime-local" class="form-control" style="background: white ; border: solid 1px"" value="data and time" id="eventend" name="eventend"></td>
        </tr>
        <tr>
            <td><label for="eventlocation">Location:</label></td>
            <td><input type="text" class="form-control" style="background: white ; border: solid 1px"" id="eventlocation" name="eventlocation"></td>
        </tr>
        <tr>
            <td><label for="eventattendee">Add guest Email:<sup>*Separate by commas</sup></label></td>
            <td><input type="text" name="eventattendee" class="form-control" style="background: white ; border: solid 1px"" id="eventattendee"></td>
        </tr>
        <tr>
            <td>
                Generate Google Meet Link
            </td>
            <td>
                <input type="radio" id="yes" name="meetinglink" value="yes">
                <label for="yes">Yes</label>
            
                <input type="radio" id="no" name="meetinglink" value="no" checked>
                <label for="no">No</label>
            </td>
        </tr>
        <tr>
            <td><input type="submit"  name="submit"></td>
            <td><a href="{{ route('event.index') }}">Back</a></td>
        </tr>
    </table>   
</form>
@endsection
