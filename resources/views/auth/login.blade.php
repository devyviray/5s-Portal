@extends('layouts.app')

@section('content')
<div class="justify-content-center wrap-div">
    <div class="container h-100">
        <div class="w-100 row align-items-center" style="height: 100%; margin-left:20px">
            <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
                <div style="height:40%"></div>
                <img class="mb-3 mt-5" style="height: 73px" src="{{ asset('/img/new_design/lfuggoc2.png') }}">
                <div class="text-center">
                    <img class="ml-auto mr-auto" style="height: 60px" src="{{ asset('/img/new_design/5s portal.png') }}">
                </div>
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
                        {{-- <div class="col-md-4"></div> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
