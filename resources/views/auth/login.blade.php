@extends('layouts.app')

@section('content')
<div class="justify-content-center wrap-div">
    <div class="row" style="height: 100%">
        <div class="col-md-11 img-login1" style="position:relative">
            <div class="row" style="height: 100%">
                <div class="col-md-2"></div>
                <div class="col-md-3">
                    <div style="height:65%"></div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf 
                        <div class="form-group">
                            <div>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email Address">
                                @error('email')
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                                @error('password')
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark" for="remember">
                                        {{ __('Remember Me ?') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <button type="submit" class="btn btn-success    "> {{ __('LOG-IN') }} </button>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-9">
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link text-dark" href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </form>
                </div>
            </div>
            </div>
            <div class="div-logo">
                <img src="{{ asset('img/new_design/lafil-logo.png')}}">
            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
</div>
@endsection
