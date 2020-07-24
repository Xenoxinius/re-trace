@extends('layouts.app')
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection
@section('content')
    <h3>private users</h3>
    @foreach($privates as $private)
        <h5>Username: "{{$private->first_name}}"</h5>
        <h6>projects</h6>
        @if(count($privateBuildings[0]) > 0)
            <ul>
                @foreach($privateBuildings as $privateBuilding)
                    @for($i = 0; $i < count($privateBuilding); $i++)
                        <li>projectName: "{{ $privateBuilding[$i]['projectName'] }}" <br> type:
                            "{{$privateBuilding[$i]['type']}}"
                        </li>
                    @endfor
                @endforeach
            </ul>
        @endif
    @endforeach
    <h3>business users</h3>
    @foreach($businesses as $business)
        <h5>Username: "{{$business->first_name}}"</h5>
        <h6>projects</h6>
        @if(count($businessBuildings[0]) > 0)
            <ul>
                @foreach($businessBuildings as $businessBuilding)
                    @for($i = 0; $i < count($businessBuilding); $i++)
                        <li>projectName: "{{ $businessBuilding[$i]['projectName'] }}" <br> type:
                            "{{$businessBuilding[$i]['type']}}"
                        </li>
                    @endfor
                @endforeach
            </ul>
        @endif
    @endforeach
@endsection