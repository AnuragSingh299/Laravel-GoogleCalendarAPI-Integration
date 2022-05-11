@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="card" style="border: none ; width: 75%">
        <div class="card-body">
            <table class="table" style="font-size: 15px ; border:none">
                <tbody>
                    <tr>
                        <td>Name:</td>
                        <td>{{ $calendar->title }}</td>
                    </tr>
                    @if($calendar->description != NULL)
                    <tr>
                        <td>Description:</td>
                        <td>{{ $calendar->description }}</td>
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