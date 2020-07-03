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


    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
            $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $(function(){
        $('#avatar').on('change',function(){
            readURL(this);
        })
    })



  </script>
@endsection

@section('content')

<div class="row">
    <div class="col-md-8">
      <div class="box box-widget">
        <div class="info-box bg-{{config('global.SITE_SETTING.ADMIN_THEME')}}">
            <span class="info-box-icon">
            <i class="fa fa-user"></i>
            </span>

            <div class="info-box-content">
              <span class="info-box-text">{{__("New User Registration")}}</span>
            <span class="info-box-number total-rows" >&nbsp;</span>

              <div class="progress">
                <div class="progress-bar" id="pbx" style="width: 100%"></div>
              </div>
              <span class="progress-description">
                {{__("New User Registration")}}
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>

        <div class="box-body">
            <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                @include('gtx::Admin.partials.flash')
                @csrf

                <div class="form-group has-feedback  @error('name') has-error @enderror">
                    <label for="exampleInputEmail1">{{__("User Name")}}</label>
                    <input type="text" id="name" name="name" value="{{old('name')}}" required autocomplete="off" autofocus class="form-control" placeholder="{{__("User Name")}}">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @error('name')
                         <span class="help-block" role="alert">
                             {{ __($message) }}
                         </span>
                     @enderror
                 </div>
                 <div class="form-group has-feedback  @error('phone') has-error @enderror">
                    <label for="exampleInputEmail1">{{__("Phone")}}</label>
                    <input type="text" id="phone" name="phone" value="{{old('phone')}}" required autocomplete="off" autofocus class="form-control" placeholder="{{ __('Phone') }}">
                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                    @error('phone')
                         <span class="help-block" role="alert">
                             {{ __($message) }}
                         </span>
                     @enderror
                 </div>
                  <div class="form-group has-feedback  @error('email') has-error @enderror">
                    <label for="exampleInputEmail1">{{__("Email")}}</label>
                    <input type="email" id="email" name="email" value="{{old('email')}}" required autocomplete="off" autofocus class="form-control" placeholder="{{ __('Email') }}">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @error('email')
                         <span class="help-block" role="alert">
                             {{ __($message) }}
                         </span>
                     @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                    <img src="{{asset('images/noimage.png')}}" style="width:180px;padding:20px;border:1px solid #dedede;border-radius:5px;margin-bottom:20px" id="blah">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback @error('avatar') has-error @enderror">
                            <label for="exampleInputEmail1">Avatar</label>
                            <input id="avatar" type="file" class="form-control" name="avatar" placeholder="">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            @error('password')
                                <span class="help-block" role="alert">
                                    {{ __($message) }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group has-feedback @error('avatar') has-error @enderror">
                            <label for="exampleInputEmail1">Roles</label>
                            {{-- <input id="avatar" type="file" class="form-control" name="avatar" placeholder=""> --}}
                            <select id="role" name="roles" class="form-control">
                                @foreach($roles as $role)
                                    <option value="{{$role->name}}">{{$role->name}}</option>
                                @endforeach
                            </select>

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
                    <a href="{{route('admin.users')}}" class="btn btn-success btn-block btn-flat"><i class="fa fa-chevron-left"></i> {{ __('Kembali') }}</a>
                      </div>
                    <div class="col-xs-6">
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-3">
                      <button type="submit" class="btn btn-success btn-block btn-flat"><i class="fa fa-save"></i> {{ __('Register') }}</button>
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
