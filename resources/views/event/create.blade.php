@extends('layouts.app')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
    select {
        width: 400px;
        height: 30px;
    }
</style>
<script>
    

    $(function () {
    $('input[name="alldaydate"]').hide();
    $('label[for="alldayevent"]').hide();
    $('label[for="event"]').hide();
    $('select[name="RecurringOptions"]').hide();
    $('input[name="recurringCheck"]').on('click', function () {
        if ($(this).prop('checked')) {
            $('select[name="RecurringOptions"]').fadeIn();
        } else {
            $('select[name="RecurringOptions"]').hide();
        }
    });
    $('input[name="allday"]').on('click', function () {
        if ($(this).prop('checked')) {
            $('input[name="alldaydate"]').fadeIn();
            $('label[for="alldayevent"]').fadeIn();
            $('input[name="eventstart"]').hide();
            $('input[name="eventend"]').hide();
        } else {
            $('input[name="alldaydate"]').hide();
            $('input[name="eventstart"]').show();
            $('input[name="eventend"]').show();
            $('label[for="alldayevent"]').show();
            $('label[for="alldayevent"]').show();
        }
    });
});

</script>
    
@section('content')
<form action="{{ route('event.store') }}" method="POST" class="form-group" style="width: 75%; margin: auto">
    @csrf
    <table class="table table-borderless" style="font-size: 15px ; background: white ; color: black ; border:none ">
        <tr>
            <td><label for="eventname">Event name:</label><br></td>
        </tr>
        <tr>
            <td><input class="form-control" style="background: white ; border: solid 1px" type="text" id="eventname" name="eventname" required><br></td>
        </tr>
        <tr>
            <td><label for="eventdesc">Event description:</label><br></td>
        </tr>
        
        <tr>
            <td><textarea name="eventdesc" class="form-control" style="background: white ; border: solid 1px"" id="" cols="20" rows="5"></textarea></td>
        </tr>
        <tr>
            <td><label for="alldayevent">All day:</label>
                <input type="checkbox" id="allday" name="allday" onclick="alldaycheck()" style="height: 20px ; width: 20px; border: solid 1px ; border-radius: 3px">
            </td>
            <td><label for="alldayevent">Select Date:</label>
                <input type="date" name="alldaydate" id="alldaydate">
            </td>
        </tr>
        <tr>
            <td> <label for="eventstart">Event Start:</label></td>
        </tr>
        <tr>
            <td><input type="datetime-local" class="form-control" style="background: white ; border: solid 1px"" value="data and time" id="eventstart" name="eventstart"></td>
        </tr>
        <tr>
            <td><label for="eventend">Event End:</label></td>
        </tr>
        <tr>
            <td><input type="datetime-local" class="form-control" style="background: white ; border: solid 1px"" value="data and time" id="eventend" name="eventend"></td>
        </tr>
        <tr>
            <td><label for="recurringCheck">Recurring Event:</label>
                <input type="checkbox" id="recurringCheck" name="recurringCheck" onclick="recurringCheck()" style="height: 20px ; width: 20px; border: solid 1px ; border-radius: 3px">
            </td>
            <td><select name="RecurringOptions" >
                <option value="1">Daily</option>
                <option value="2">Every WeekDay(Monday to Friday)</option>
                <option value="3">Custom</option>
              </select>    
            </td>
        </tr>
        
        <tr>
            <td><label for="eventlocation">Location:</label></td>
        </tr>
        <tr>
            <td><input type="text" class="form-control" style="background: white ; border: solid 1px"" id="eventlocation" name="eventlocation">
            </td>
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
{{-- @extends('layouts.app')
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
@endsection --}}
