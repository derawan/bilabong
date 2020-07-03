@extends("gtx::Admin.layouts.app")

@section('plugin_styles')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection

@section('plugin_scripts')
<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
@endsection

@section('styles')
<style>
  .dataTables_paginate  { padding:2px }
  .toolbar {padding:5px; padding-left:20px}
  .dt-table-header .table-filter {
    padding:0px;
  }
</style>
@endsection

@section('scripts')
<script>


  </script>
@endsection

@section('content')

<div class="row">
    <div class="col-md-8">
      <div class="box box-widget">
        <div class="info-box bg-{{config('global.SITE_SETTING.ADMIN_THEME')}}">
            <span class="info-box-icon">
            <img src="{{$user->getAvatar()}}" style="width:60px;margin-top:-15px;border:2px solid #ffffff" class="img-circle"/>
            </span>

            <div class="info-box-content">
              <span class="info-box-text">Perubahan Data Pengguna</span>
            <span class="info-box-number total-rows" >{{$user->name}}</span>

              <div class="progress">
                <div class="progress-bar" id="pbx" style="width: 100%"></div>
              </div>
              <span class="progress-description">
                Perbaharui data pengguna ini
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>

        <div class="box-body">
            @include('gtx::Admin.partials.flash')
            <form method="POST" action="{{ route('admin.members.update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="token" value="{{$token}}">
                <div class="form-group has-feedback  @error('name') has-error @enderror">
                    <label for="exampleInputEmail1">Nama Pengguna</label>
                    <input type="text" id="name" name="name" value="{{$user->name}}" required autocomplete="off" autofocus class="form-control" placeholder="{{ __('Name') }}">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @error('name')
                         <span class="help-block" role="alert">
                             <strong>{{ __($message) }}</strong>
                         </span>
                     @enderror
                 </div>
                 <div class="form-group has-feedback  @error('phone') has-error @enderror">
                    <label for="exampleInputEmail1">No Telp.</label>
                    <input type="text" id="phone" name="phone" value="{{$user->phone}}" required autocomplete="off" autofocus class="form-control" placeholder="{{ __('Phone') }}">
                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                    @error('phone')
                         <span class="help-block" role="alert">
                             <strong>{{ __($message) }}</strong>
                         </span>
                     @enderror
                 </div>
                  <div class="form-group has-feedback  @error('email') has-error @enderror">
                    <label for="exampleInputEmail1">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{$user->email}}" required autocomplete="off" autofocus class="form-control" placeholder="{{ __('E-Mail Address') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @error('email')
                         <span class="help-block" role="alert">
                             <strong>{{ __($message) }}</strong>
                         </span>
                     @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <img src="{{$user->getAvatar()}}" style="width:180px;padding:20px;border:1px solid #dedede;border-radius:5px;margin-bottom:20px" id="blah">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback @error('avatar') has-error @enderror">
                            <label for="exampleInputEmail1">Avatar</label>
                            <input id="avatar" type="file" class="form-control" name="avatar" placeholder="">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            @error('password')
                                <span class="help-block" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>





                  <div class="row">
                    <div class="col-xs-3">
                    <a href="{{route('admin.members')}}" class="btn btn-success btn-block btn-flat">{{ __('Kembali') }}</a>
                      </div>
                    <div class="col-xs-6">
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-3">
                      <button type="submit" class="btn btn-success btn-block btn-flat">{{ __('Perbaharui') }}</button>
                    </div>
                    <!-- /.col -->
                  </div>

              </form>
        </div>
        <!-- /.box-body -->
        <div class="box-footer box-comments">

        </div>
        <!-- /.box-footer -->
        <div class="box-footer">

        </div>
        <!-- /.box-footer -->
      </div>
      <!-- /.box -->
    </div>

  </div>


@endsection
