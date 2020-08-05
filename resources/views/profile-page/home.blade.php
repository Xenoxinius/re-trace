@extends('layouts.app')
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
@endsection
@section('head-script')
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
@endsection
@section('content')
    <!--
HOMEPAGE for users, users find their projects here and functionality to upload files/materiallists
-->
    @if(session('verified'))
        <div class="alert alert-success">
            You've successfully verified your email!
        </div>
    @endif
    <div class="d-flex flex-md-row flex-column justify-content-between">
        <div class="col-sm-6 col-12 px-2 card" id="userInfo">
            <div class="row no-gutters d-flex">
                <div class="col-auto d-flex pl-4 pt-4">
                    <img src="{{ asset('images/coolbuilding.jpg') }}" class="w-50" alt="">
                </div>
                <div class="col-4 d-flex flex-center">
                    <div>
                        <h4>Hi {{ Auth::user()->first_name }}</h4>
                    </div>
                </div>
            </div>
            <div class="row no-gutters d-flex">
                <div class="col-12 d-flex flex-column pl-4 pt-4">
                    <h5>Personal details</h5>
                    <ul>
                        <li>Full name: {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</li>
                        <li>Email address: {{ Auth::user()->email }}</li>
                        <li>Profile Type: {{ Auth::user()->type }}</li>
                    </ul>
                </div>
            </div>

            <div class="card-title mt-3 ml-3"><h4>My projects</h4></div>
            <div class="card-body">
                @if(count($buildings) == 0)
                    <h5> - Please add your first project to progress your profile</h5>
                @endif
                <ul>
                    @foreach($buildings as $building)
                        <li class="mb-1 d-flex justify-content-between">
                            <a id="project-names"
                               href="{{route('dash', $building->id)}}"> {{ $building->projectName ?? 'Project name' }}</a>
                            <div>
                                @if(Auth::user()->type == 'admin')
                                    <button data-toggle="modal"
                                            data-target="#myModal" class="btn btn-primary" name="deleteBuilding"
                                            id="main-button-small">Delete
                                    </button>
                                @endif
                            </div>
                        </li>
                        <hr>
                        <div id="myModal" class="modal fade" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content text-left">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">X
                                        </button>
                                        <h4 class="modal-title">Are you sure you want to delete?</h4>
                                    </div>
                                    <form action="{{ route('deleteBuilding', $building) }}" method="post">
                                        @csrf
                                        <div class="modal-body">
                                            <button value="{{ $building->id }}" class="btn btn-primary"
                                                    name="deleteBuilding" id="main-button">Yes, delete project
                                            </button>
                                            <button type="button" class="btn btn-default" id="secondary-button-small"
                                                    data-dismiss="modal">No
                                            </button>
                                        </div>
                                        <div class="modal-footer">

                                        </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    @endforeach
                    <div class="d-flex">
                        {{ $buildings->links() }}
                    </div>
                </ul>

            </div>
            <a class="btn btn-primary ml-2 mb-2" id="main-button" href="{{ route('building') }}">Add New Project</a>

        </div>
        <div class="col-sm-5 col-12 p-2 card d-flex" id="projectInfo">
            <div class="row d-flex">
                <div class="col-12 d-flex align-items-center pl-5" id="newSearch">
                    <form class="form">
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Search" aria-label="Search"
                                   style="padding-left: 20px; border-radius: 40px;" id="mysearch">
                            <div class="input-group-addon py-1"
                                 style="margin-left: -50px; z-index: 3; border-radius: 40px; border:none;">
                                <button class="btn btn-warning btn-sm" type="submit" style="border-radius: 20px;"
                                        id="search-btn"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="map" class=" border border-dark mb-5 ml-5 mr-5 rounded"></div>
            </div>
        </div>
    </div>


    <?php use App\Http\Controllers\HomeController;
    use App\Building; ?>

    <script defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQxZFeQzEx6mmfOypA8Q4uZOU5zmO6lS0&callback=initMap">
    </script>
    <script type="text/javascript">
        "use strict";

        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        var labelIndex = 0;

        const BELGIUM_BOUNDS = {
            north: 49.56,
            south: 51.47,
            west: 2.59,
            east: 6.26
        };
        const ANTWERPEN = {
            lat: 51.22,
            lng: 4.4
        };

        function initMap() {

            let locationArray = [];
                @for($i=0; $i < count( $locations ); $i++)
            var location = {
                    lat: {!! HomeController::getLat($locations[$i]) !!},
                    lng: {!! HomeController::getLng($locations[$i]) !!}};
            locationArray.push(location);
                @endfor

            let map = new google.maps.Map(document.getElementById("map"), {
                    center: ANTWERPEN,
                    restriction: {
                        latLngBounds: BELGIUM_BOUNDS,
                        strictBounds: false
                    },
                    zoom: 8
                });
                {{-- write a check that only displays buildings with the correct materials --}}
                {{--            @if((Building::where('projectName', 'fff1234')->first())== null)--}}
            for (let i = 0; i < locationArray.length; i++) {
                new google.maps.Marker({
                    position: locationArray[i],
                    label: labels[labelIndex++ % labels.length],
                    map: map
                });
            }
            {{--            @endif--}}
        }
    </script>
    {{--  <div class="container mt-3">
          Profile Progress
          --}}{{--            <div class="progress">--}}{{--
          <div>
              @if (!isset($firstbuilding->projectName))
                  <h2><strong>Please add a first project to progress your profile </strong></h2>
              @else
                  <h3><strong>Your profile is up to date! Click your project names to edit and add files</strong></h3>
              @endif
          </div>
      </div>--}}


@endsection
