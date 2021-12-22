@extends('masterlayout')
@section('titlename') Login @endsection
@section('maincontent')

<!-- Section -->
        <section class="bg-section ysuccess pt-10 pb-10" data-black-overlay="8" style="background-image: url({{ asset('public/images/background/bg-2.jpg') }})">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <i class="fa fa-home white"></i> <span> / Login</span> 
                    </div>
                    <div class="col-lg-5 align-right">
                        @if (Route::has('login'))
                            <div class="top-right links">
                                
                                    @if (Route::has('register'))
                                       <a class="btn btn-success mb-0" href="{{ route('register') }}"><i class="fa fa-user-plus"></i> {{ __('Register') }}</a>
                                    @endif 
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <!-- /Section -->


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8" style="padding-bottom: 100px; padding-top: 100px">
            <div class="card111">

                <div class="card-body111" >
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" style="color:#444;" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
