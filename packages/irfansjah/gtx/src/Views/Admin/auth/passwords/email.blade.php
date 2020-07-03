@extends('gtx::Admin.layouts.login')

@section("scripts")
@endsection

@section('content')
<div class="login-box">
    <div class="box box-widget widget-user">
        <div class="widget-user-header bg-black" style="background: url('{{asset('assets/themes/adminlte/img/photo2.png')}}') center center;">
          <h3 class="widget-user-username">{{config('global.SITE_INFO.SITE_TITLE')}}</h3>
          <h5 class="widget-user-desc">{{config('app.version')}}</h5>
        </div>
        <div class="widget-user-image">
          <img class="img-circle" src="{{asset('assets/themes/adminlte/img/AdminLTELogo.png')}}" alt="User Avatar">
        </div>
        <div class="box-footer">
            @if (session('status'))
            <div class="row" style="margin-top:20px">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            </div>

            @endif

          <div class="row">

<div class="col-md-12" style="margin-top:20px">
    <form method="POST" action="{{ $emailpassword_route }}">
        @csrf
    <p class="login-box-msg">{{ __('Reset Password') }}</p>
        <div class="form-group has-feedback @error('email') has-error @enderror">
          <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="off" autofocus placeholder="{{ __('E-Mail Address') }}">

          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

           @error('email')
           <span class="help-block" role="alert">{{ $message }}</span>
          @enderror
          {{-- <input class="form-control " required autocomplete="email" autofocus> --}}
        </div>


        <div class="row">
          <div class="col-xs-4"><a href="{{ $login_route }}" class="text-center btn btn-flat">{{__("Log In")}}</a></div>
          <div class="col-xs-8"><button type="submit" class="btn btn-primary btn-block btn-flat"><i class="glyphicon glyphicon-log-in"></i>  &nbsp; {{ __('Send Password Reset Link') }}</button></div>

        </div>




            {{-- <div class="social-auth-links text-center">
                <p>- OR -</p>
                <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
                <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
            </div> --}}
      </form>

</div>

          </div>
        </div>
      </div>

</div>
@endsection
