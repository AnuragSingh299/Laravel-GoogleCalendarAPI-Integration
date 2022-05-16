@extends('layouts.app')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
    /* select {
        width: 400px;
        height: 30px;
    } */
    /* body {
        background: #EFEFBB;  
    }
    input {
        border: 2px solid white;
        border-radius: 2px;
    }
    input[type='checkbox'] {
        border: 2px solid white;
        border-radius: 2px;
        height: 20px;
        width: 40px;
    }
    label {
        color: rgb(11, 23, 63);
        text-shadow: ;
        margin-bottom: 10px;
    }
    input[type=text]:focus, textarea:focus {
        box-shadow: 0 0 9px white;
        font-size: 15px;
        font-weight: bold;
        border: 2px solid black;
    }
    .form-back {
        border: 2px solid black;
        border-radius: 4px;
        box-shadow: 20px;
        padding: 50px 50px 50px 50px;
    } */
</style>
<script>
    $(function () {
    $('input[name="alldaydate"]').hide();
    
    $('label[for="event"]').hide();
    $('select[name="RecurringOptions"]').hide();
    $('label[for="alldaystartdate"]').hide();
    $('label[for="alldayenddate"]').hide();
    $('input[name="alldaystartdate"]').hide();
    $('input[name="alldayenddate"]').hide();
    $('input[name="recurringCheck"]').on('click', function () {
        if ($(this).prop('checked')) {
            $('select[name="RecurringOptions"]').fadeIn();
        } else {
            $('select[name="RecurringOptions"]').hide();
        }
    });
    $('input[name="allday"]').on('click', function () {
        if ($(this).prop('checked')) {
            $('input[name="alldaystartdate"]').fadeIn();
            $('label[for="alldayevent"]').fadeIn();
            $('label[for="alldaystartdate"]').fadeIn();
            $('label[for="alldayenddate"]').fadeIn();
            $('input[name="alldaystartdate"]').fadeIn();
            $('input[name="alldayenddate"]').fadeIn();
            $('input[name="eventstart"]').hide();
            $('input[name="eventend"]').hide();
            $('label[for="eventend"]').hide();
            $('label[for="eventstart"]').hide();
        } else {
            
            $('input[name="eventstart"]').show();
            $('input[name="eventend"]').show();
            $('label[for="alldayevent"]').show();
            $('label[for="alldayevent"]').show();
            $('label[for="alldaystartdate"]').hide();
            $('label[for="alldayenddate"]').hide();
            $('label[for="eventend"]').show();
            $('label[for="eventstart"]').show();
            $('input[name="alldaystartdate"]').hide();
            $('input[name="alldayenddate"]').hide();
        }
    });
    
});

</script>
    
@section('content')
<form action="{{ route('event.store') }}" method="POST" class="form-group" style="width: 50%; ">
    @csrf
        <div class="form-back">
            <div>
                <label for="eventname">Event name:</label><br>
                <input class="form-control" type="text" id="eventname" name="eventname" required>
            </div>
            
            <div>
                <label for="eventdesc">Event description:</label><br></td>
                <textarea name="eventdesc" class="form-control"  id="" cols="20" rows="5"></textarea>
            </div>
           
            <div>
                <label for="alldayevent">All day:</label>
                <input type="checkbox" id="allday" name="allday" value="yes"  >
            </div>
            
            <div>
                <label for="alldaystartdate">Start Date:</label>
                <input type="date" name="alldaystartdate" id="alldaystartdate">
            </div>

            <div>
                <label for="alldayenddate">End Date:</label>
                <input type="date" name="alldayenddate" id="alldayenddate">
            </div>
            
            <div>
                <label for="eventstart">Event Start:</label>
                <input type="datetime-local" class="form-control"  value="data and time" id="eventstart" name="eventstart">
            </div>
            
            <div>
                <label for="eventend">Event End:</label>
                <input type="datetime-local" class="form-control"  value="data and time" id="eventend" name="eventend">
            </div>
        
            <div>
                <label for="recurringCheck">Recurring Event:</label>
                <input type="checkbox" id="recurringCheck" name="recurringCheck" value="yes" onclick="recurringCheck()" >
            </div>
            
            <div>
                <select name="RecurringOptions">
                    <option value="daily">Daily</option>
                    <option value="weekday">Every WeekDay(Monday to Friday)</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
                
            <div>
                <label for="eventlocation">Location:</label>
                <input type="text" class="form-control"  id="eventlocation" name="eventlocation">    
            </div>
            
            <div>
                <label for="eventattendee">Add guest Email:</label>
                <input type="text" name="eventattendee" class="form-control"  id="eventattendee">    
            </div>
            
            <div>
                <label>Google Meet Link:</label><br>
                <input type="radio" id="yes" name="meetinglink" value="yes">
                <label for="yes">Yes</label>
                <input type="radio" id="no" name="meetinglink" value="no" checked>
                <label for="no">No</label>
            </div>
                
            <div>
                <input class="btn btn-primary" type="submit"  name="submit">
                <a href="{{ route('event.index') }}"  class="btn btn-primary">Back</a>
            </div>
        </div>
            
</form>
@endsection