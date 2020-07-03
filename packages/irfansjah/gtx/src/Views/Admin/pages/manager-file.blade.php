@extends("gtx::Admin.layouts.app")
@section('content')
<div class="box box-widget">
      <div class="info-box bg-{{config('global.SITE_SETTING.ADMIN_THEME')}}" style="margin:0px">
        <span class="info-box-icon"><i class="ion ion-folder"></i></span>

        <div class="info-box-content">
          <span class="info-box-number">File Manager</span>

          <span class="progress-description">
          {{__("This is where you can manage your files which are defined on filesystem config")}}
              </span>
        </div>
      </div>
    <div class="box-body" style="padding-top:0px">
        <div id="fm" style="padding-top:0px"></div>
    </div>
    <div class="box-footer bg-{{config('global.SITE_SETTING.ADMIN_THEME')}}">

    </div>

  </div>
@endsection
@section('plugin_scripts')
<script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>

@endsection
