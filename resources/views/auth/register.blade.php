@extends('layouts.app')
@section('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection
@section('content')
<div class="container text-center">
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <img class="mb-4" src="{{ asset('/images/retracelogo.png') }}" alt="" height="40">
        <h1 class="h3 mb-3 font-weight-normal">Re-trace.io</h1>
        <div class="form-group row d-flex justify-content-center">
                <div class="w-25">
                        <label for="first_name" class="sr-only">{{ __('First name') }}</label>
                        <input id="first_name" type="text"
                               class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                               value="{{ old('first_name') }}" placeholder="{{ __('First name') }}" required
                               autocomplete="first_name" autofocus>

                        @error('first_name')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                </div>
        </div>
        <div class="form-group row d-flex justify-content-center">
            <div class="w-25">
                <label for="last_name" class="sr-only">{{ __('Last name') }}</label>
                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" placeholder="{{ __('Last name') }}" required autocomplete="last_name" autofocus>

                @error('last_name')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row d-flex justify-content-center">
            <div class="w-25">
                <label for="email" class="sr-only">{{ __('E-Mail Address') }}</label>
                 <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email"
                           value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" required autocomplete="email">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
            </div>
        </div>
        <div class="form-group row d-flex flex-nowrap justify-content-center">
                <label for="type" class="sr-only">{{ __('type') }}</label>

                <div class="col-md-6">
                    <input id="type" type="radio"  name="type" value="Business">
                    <label for="type">Business</label>
                    <button class="text-center" id="custom-tooltip" tabindex="-1" data-toggle="tooltip" data-placement="bottom" title="When you're a single home owner">
                        ?
                    </button>
                    <input id="type2" type="radio"  name="type" value="Private">
                    <label for="type2">Private</label>
                    <button class="text-center" id="custom-tooltip" tabindex="-1" data-toggle="tooltip" data-placement="bottom" title="When you're a developer or recycling plant">
                        ?
                    </button>
                    @error('type')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
        </div>
        <div class="form-group row d-flex justify-content-center">
            <div class="w-25">
                <label for="password" class="sr-only">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="new-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group row d-flex justify-content-center">
            <div class="w-25">
                <label for="password-confirm" class="sr-only">Confirm password</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm password" required autocomplete="new-password">
            </div>
        </div>
        <div class="form-group row d-flex justify-content-center">
            <div>
                <button type="submit" class="btn btn-primary" id="auth-button">
                    {{ __('Register') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

