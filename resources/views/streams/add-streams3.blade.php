@extends('layouts.app')
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/add_streams.css') }}">
@endsection
@section('head-script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@endsection
@section('content')
    <!--
blade for adding a new building/project to a User
-->
    <div class="container d-flex justify-content-center flex-column align-items-center">
        <h3>ADD NEW STREAM</h3>
        <div class="card d-flex justify-content-center" id="set-width">
            <div class="mb-4 text-center card-header">
                <img src="{{ asset('/images/retracelogo.png') }}" alt="" height="40">
                <h3><strong>re-trace.io</strong></h3>
            </div>
            <div class="card-body text-center">
                <form action="{{ route('add-streams3', $id) }}" method="post">
                    @csrf
                    <h4>Which materials are in your stream?</h4>
                    <div class="form-group mt-3">
                        <div class="row d-flex justify-content-center">
                            <input placeholder="Filter..." class="text-center" type="text" name="filter"
                                   id="filterCategories"/>
                        </div>
                        <div class="row d-flex justify-content-center">
                            {{--                            <select name="substance[]" id="categorySelect" multiple>
                                                            @foreach($substanceHeadCategories as $substanceHeadCategory)
                                                                <option value="{{ $substanceHeadCategory->id }}" class="categoryOptions">
                                                                    <p>{{ $substanceHeadCategory->name }}</p>
                                                                </option>
                                                            @endforeach
                                                            @foreach($substanceSubCategories1 as $substanceSubCategory1)

                                                                <option value="{{ $substanceSubCategory1->id }}" class="categoryOptions">
                                                                    ---{{  $substanceSubCategory1->name }}
                                                                </option>

                                                            @endforeach
                                                            @foreach($substanceSubCategories2 as $substanceSubCategory2)

                                                                <option value="{{ $substanceSubCategory2->id }}" class="categoryOptions">
                                                                    {{ $substanceSubCategory2->name }}
                                                                </option>

                                                            @endforeach
                                                        </select>--}}
                            <div id="categorySelect">
                                @foreach($substanceSubCategories2 as $substanceSubCategory2)
                                        <input type="checkbox" value="{{ $substanceSubCategory2->id }}" class="vis-hidden" id="{{ $substanceSubCategory2->id }}"
                                               name="substance[]">
                                        <label for="{{ $substanceSubCategory2->id }}" class="categoryOptions">{{ $substanceSubCategory2->name }}</label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <h4>What are the functions of your stream?</h4>
                    <div class="form-group mt-3">
                        <div class="row d-flex justify-content-center">
                            <input placeholder="Filter..." class="text-center" type="text" name="filter"
                                   id="filterCategories2"/>
                        </div>
                        <div class="row d-flex justify-content-center">
{{--                            <select name="materialFunction[]" id="categorySelect2" multiple>
                                @foreach($functionHeadCategories as $functionHeadCategory)
                                    <option value="{{ $functionHeadCategory->id }}" class="categoryOptions2">
                                        {{ $functionHeadCategory->name }}
                                    </option>
                                @endforeach
                                @foreach($functionSubCategories1 as $functionSubCategory1)

                                    <option value="{{ $functionSubCategory1->id }}" class="categoryOptions2">
                                        ---{{ $functionSubCategory1->name }}
                                    </option>

                                @endforeach
                                @foreach($functionSubCategories2 as $functionSubCategory2)

                                    <option value="{{ $functionSubCategory2->id }}" class="categoryOptions2">
                                        {{ $functionSubCategory2->name }}
                                    </option>

                                @endforeach
                            </select>--}}
                            <div id="categorySelect2">
                                @foreach($functionSubCategories2 as $functionSubCategory2)
                                    <input type="checkbox" value="{{ $functionSubCategory2->id }}" class="vis-hidden" id="{{ $functionSubCategory2->id }}"
                                           name="materialFunction[]">
                                    <label for="{{ $functionSubCategory2->id }}" class="categoryOptions2">{{ $functionSubCategory2->name }}</label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <button type="submit" id="main-button-wide" class="btn btn-primary" name="newStream">Next</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('streams2', $id) }}"><span><strong>Go Back</strong></span></a>
            </div>
        </div>
    </div>
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#filterCategories').keyup(function () {
                var filter = $(this).val();
                $('.categoryOptions').each(function () {
                    if ($(this).text().toLowerCase().includes(filter.toLowerCase())) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                    $('#categorySelect').text().toLowerCase().includes(filter.toLowerCase());
                })
            })
        })
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#filterCategories2').keyup(function () {
                var filter = $(this).val();
                $('.categoryOptions2').each(function () {
                    if ($(this).text().toLowerCase().includes(filter.toLowerCase())) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                    $('#categorySelect2').text().toLowerCase().includes(filter.toLowerCase());
                })
            })
        })
    </script>
@endsection
@endsection
