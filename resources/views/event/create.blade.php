@extends('layouts.app')
<script>

</script>
@section('content')
<form action="{{ route('event.store') }}" method="POST" class="form-group" style="width: 75%; margin: auto">
    @csrf
    <table class="table" style="font-size: 20px ; background: white ; color: black ">
        <th>Add new event</th>
        <tr>
            <td><label for="eventname">Event name:</label><br></td>
            <td><input type="text" id="eventname" name="eventname"><br></td>
        </tr>
        <tr>
            <td><label for="eventdesc">Event description:</label><br></td>
            <td><textarea name="eventdesc" id="" cols="30" rows="10"></textarea></td>
        </tr>
        <tr>
            <td> <label for="eventstart">Event Start:</label></td>
            <td><input type="datetime-local" value="data and time" id="eventstart" name="eventstart"></td>
        </tr>
        <tr>
            <td><label for="eventend">Event End:</label></td>
            <td><input type="datetime-local" value="data and time" id="eventend" name="eventend"></td>
        </tr>
        <tr>
            <td><label for="eventlocation">Location:</label></td>
            <td><input type="text" id="eventlocation" name="eventlocation"></td>
        </tr>
        <tr>
            <td><label for="eventattendee">Add guest Email:<sup>*Separate by commas</sup></label></td>
            <td><textarea name="eventattendee" id="" cols="30" rows="10"></textarea></td>
        </tr>
        <tr>
            <td>
                Generate Google Meet Link
            </td>
            <td>
                <input type="radio" id="yes" name="meetinglink" value="true">
                <label for="yes">Yes</label>
            
                <input type="radio" id="no" name="meetinglink" value="false" checked>
                <label for="no">No</label>
            </td>
        </tr>
        <tr>
            <td><input type="submit" class="btn btn-success" name="submit"></td>
            <td><a href="{{ route('event.index') }}" class="btn btn-warning">Back</a></td>
        </tr>
    </table>   
</form>
@endsection
