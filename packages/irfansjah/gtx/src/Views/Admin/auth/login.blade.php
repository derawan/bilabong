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
          <div class="row">

<div class="col-md-12" style="margin-top:20px">
    <form method="POST" action="{{ $login_route }}">
        @csrf
    <p class="login-box-msg">{{__("Sign in to start your session")}}</p>
        <div class="form-group has-feedback @error('email') has-error @enderror">
          <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="off" autofocus placeholder="{{ __('E-Mail Address') }}">

          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

           @error('email')
           <span class="help-block" role="alert">{{ $message }}</span>
          @enderror
          {{-- <input class="form-control " required autocomplete="email" autofocus> --}}
        </div>

        <div class="form-group has-feedback @error('password') has-error @enderror">
          <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password" placeholder="{{ __('Enter Password') }}">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          @error('password')<span class="help-block" role="alert">{{ $message }}</span>@enderror
        </div>
        <div class="row">
          <div class="col-xs-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox">  {{ __('Remember Me') }}
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-xs-4"><button type="submit" class="btn btn-primary btn-block btn-flat"><i class="glyphicon glyphicon-log-in"></i> &nbsp; {{__("Sign In")}}</button></div>
          <!-- /.col -->
        </div>
        <a href="{{ $passwordreset_route }}"> {{ __('Forgot Your Password?') }}</a><br>
        <a href="{{ $register_route }}" class="text-center">{{__("Register a new membership")}}</a>



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
