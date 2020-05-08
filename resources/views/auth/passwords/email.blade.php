@extends('layouts.app')

@section('content')
<div class="justify-content-center wrap-div">
    <div class="row" style="height: 100%">
        <div class="col-md-11 img-login1" style="position:relative">
            <div class="row" style="height: 100%">
                <div class="col-md-2"></div>
                <div class="col-md-3">
                    <div style="height:65%"></div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group row mt-4">
                            <div class="col-md-12   ">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="div-logo">
                <img src="{{ asset('img/new_design/lafil-logo.png')}}">
            </div>
        <div class="col-md-1"></div>
    </div>
</div>

@endsection
