@extends('layouts.app')

@section('content')
<div class="row justify-content-center wrap-div">
        <div class="col-md-6 left-div">
            <span class="web-title">5S PORTAL</span>
        </div>
        <div class="col-md-6 right-div">
            <div class="div-logo">
                <img src="{{ asset('img/lafil-logo.png')}}">
            </div>
            <div class="row right-div-inner">
                <div class="col-md-10">
                    <div style="height:50%"></div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="card-title">
                                <div class="title">{{ __('Reset Password') }}</div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form method="POST" action="{{ route('password.update') }}">
                                    @csrf
            
                                    <input type="hidden" name="token" value="{{ $token }}">
            
                                    <div>
                                        <input id="email" type="email" class="form-control mb-2 @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email Address">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
            
                                    <div>        
                                        <input id="password" type="password" class="form-control mb-2 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Enter New Password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
            
                                    <div>
                                        <input id="password-confirm" type="password" class="form-control mb-2" name="password_confirmation" required autocomplete="new-password" placeholder="Enter Confirm Password">
                                    </div>
            
                                    <div class="form-group row mt-4">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Reset Password') }}
                                            </button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </div>
@endsection
