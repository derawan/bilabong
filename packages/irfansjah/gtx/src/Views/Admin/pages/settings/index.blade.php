@extends('gtx::Admin.layouts.app')


@section('content')

<div class="row">
    <div class="col-md-12">
      <div class="box box-widget">
        <div class="info-box bg-{{config('global.SITE_SETTING.ADMIN_THEME')}}">
            <span class="info-box-icon">
            <i class="fa fa-gear"></i>
            </span>

            <div class="info-box-content">
              <span class="info-box-text">{{__("APPLICATION SETTING")}}</span>
            <span class="info-box-number total-rows" ></span>

              <div class="progress">
                <div class="progress-bar" id="pbx" style="width: 100%"></div>
              </div>
              <span class="progress-description">
                {{__("Manage Application Overall System Settings")}}
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>

        <div class="box-body">
            @include('gtx::Admin.partials.flash')
            <form method="POST" action="{{ route('admin.config.store') }}" enctype="multipart/form-data">
                @csrf
                @if($cfgs = Irfansjah\gtx\Models\SystemConfig::orderBy('group')->get())@endif
                @if($group = '')@endif
                <table class="table table-bordered">
                    @foreach($cfgs as $cfg)
                        @if($group!=$cfg->group)
                            <tr><th colspan="3"><i class="fa fa-gears"></i> {{$cfg->group}}</th></tr>
                            @if($group=$cfg->group)@endif
                        @endif
                        <tr>
                        <td style="width:20px;border:none">&nbsp;</td>
                        <td style="">{{$cfg->name}}</td><td>
                            @if($md=preg_split("/\;/",$cfg->options))@endif
                                <input type="text" class="form-control" name="sets[{{$cfg->group}}][{{$cfg->name}}]" value="{{$cfg->value}}">
                          </td>
                        </tr>

                    @endforeach
                    <tr><td colspan="2"><button type="submit" class="btn btn-info btn-flat">Save</button></td></tr>
                </table>
            </form>
        </div>
        <!-- /.box-body -->
        <div class="box-footer box-comments">

        </div>
        <!-- /.box-footer -->
        <div class="box-footer bg-{{config('global.SITE_SETTING.ADMIN_THEME')}}">

        </div>
            <!-- /.box-footer -->
      </div>
      <!-- /.box -->
    </div>

  </div>


@endsection
