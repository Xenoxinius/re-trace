@extends('layouts.app')
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/add_streams.css') }}">
@endsection
@section('content')
    <!--
blade for adding a new building/project to a User
-->
    <div class="container d-flex justify-content-center flex-column align-items-center">
        <h3>{{ __("ADD NEW STREAM") }}</h3>
        <div class="card d-flex justify-content-center">
            <div class="mb-4 text-center card-header">
                <img src="{{ asset('/images/retracelogo.png') }}" alt="" height="40">
                <h3><strong>re-trace.io</strong></h3>
            </div>
            <div class="card-body text-center">
                <h4>Hi {{ Auth::user()->first_name }},</h4>
                <br>
                <h4>{{ __("What is the name of your stream?") }}</h4>
                <div class="d-flex flex-column">
                    <div class="row mb-2">
                        <img src="{{ $targetFile && session()->get('image')? $image_data : asset('images/logos/resquare.png')}}" id="streamPic" class="w-50 mx-auto" alt="Placeholder">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="button" id="secondary-button" class="btn btn-primary" data-toggle="modal"
                                data-target="#myModal">
                            {{ __("Upload image") }}
                        </button>
                    </div>
                </div>
                <form action="{{ route('add-streams1', $project->id) }}" method="post" class="mt-4">
                    @csrf
                    <div class="form-group w-50 m-auto">
                        <label for="streamName" class="sr-only">{{ __("Name") }}:</label>
                        <input type="text" class="form-control text-center" id="streamName" name="streamName"
                               placeholder="{{ __("NAME") }}"
                               value="{{ session()->get('stream.name') ?? $inputStreamName }}">
                    </div>
                    {{--Old descriptions--}}
                    <div class="form-group w-50 m-auto">
                        <label for="streamDescription" class="sr-only">{{ __("Description") }}:</label>
                        <textarea class="form-control text-center" id="streamDescription" name="streamDescription"
                               placeholder="{{ __("DESCRIPTION") }}">{{ session()->get('stream.description') ?? $inputStreamDescription }}</textarea>
                    </div>
                    <div class="form-group w-50 m-auto pt-4">
                        {{ __("PLEASE SELECT AN ACTION") }}
                        <div class="radio-toolbar">
                            <input type="radio" id="radioApple" name="streamAction" value="reuse"
                            @if( session()->get('stream.action') == "reuse")
                                checked
                                @endif>
                            <label for="radioApple">{{ __("Reuse") }}</label>

                            <input type="radio" id="radioBanana" name="streamAction" value="recycle"
                                   @if( session()->get('stream.action') == "recycle")
                                   checked
                                @endif>
                            <label for="radioBanana">{{ __("Recycle") }}</label>
                        </div>
                    </div>
                    <button type="submit" id="main-button" class="btn btn-primary" name="newStream">{{ __("Next") }}</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <a href="{{route('dash', $project->id)}}"><span><strong>{{ __("Go Back") }}</strong></span></a>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title">{{ __("Please upload a new image")}}</h4>
                </div>
                <form action="{{ route('uploadStreamImage') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="text" name="name">
                        <input type="file" name="streamImage" id="streamImage">
                        <input value="{{ $project->id }}" type="hidden" name="projectId"/>
                        <input type="hidden" name="streamNameModal" id="streamNameModal"/>
                        <input type="hidden" name="streamDescriptionModal" id="streamDescriptionModal"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __("Close") }}
                        </button>
                        <input type="submit" value="upload" name="upload" id="uploadImage"/>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#secondary-button').click(function(){
                $('#streamNameModal').val($('#streamName').val());
                $('#streamDescriptionModal').val($('#streamDescription').val());
            });
        });
    </script>
@endsection
