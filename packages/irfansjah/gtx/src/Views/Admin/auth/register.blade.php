@extends('gtx::Admin.layouts.login')

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
    <form method="POST" action="{{ $register_route }}" enctype="multipart/form-data">
        @csrf
    <p class="login-box-msg">{{ __('User Registration Form') }}</p>
        <div class="form-group has-feedback @error('name') has-error @enderror">
            <input type="text" id="name" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="off" autofocus placeholder="{{ __('User Name') }}">

            <span class="glyphicon glyphicon-user form-control-feedback"></span>

            @error('name')
            <span class="help-block" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group has-feedback @error('phone') has-error @enderror">
            <input type="text" id="phone" class="form-control" name="phone" value="{{ old('phone') }}" required autocomplete="off" autofocus placeholder="{{ __('Phone') }}">

            <span class="glyphicon glyphicon-phone form-control-feedback"></span>

            @error('phone')
            <span class="help-block" role="alert">{{ $message }}</span>
            @enderror
        </div>

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
        <div class="form-group has-feedback @error('password') has-error @enderror">
            <input type="password" id="password-confirm" name="password_confirmation" class="form-control" required autocomplete="current-password" placeholder="{{ __('Re-Type Password') }}">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            @error('password')<span class="help-block" role="alert">{{ $message }}</span>@enderror
          </div>

          <div class="form-group has-feedback @error('avatar') has-error @enderror">
            <input type="file" id="avatar" name="avatar" class="form-control" required autocomplete="off" placeholder="{{ __('Select Your Image') }}">
            <span class="glyphicon glyphicon-picture form-control-feedback"></span>
            @error('avatar')<span class="help-block" role="alert">{{ $message }}</span>@enderror
          </div>

        <div class="row">
          <div class="col-xs-4"><a href="{{ $login_route }}" class="text-center btn btn-flat">{{__("Log In")}}</a></div>
          <div class="col-xs-8"><button type="submit" class="btn btn-primary btn-block btn-flat"><i class="glyphicon glyphicon-log-in"></i> &nbsp; {{__("Register")}}</button></div>
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
