@extends("gtx::Admin.layouts.app")

@section('plugin_styles')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/iCheck/all.css')}}">

@endsection

@section('plugin_scripts')
<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}"></script>

@endsection

@section('scripts')
<script>
@if($column_data=[])@endif
    @foreach($form_def["column_def"] as $column)
        @if($column_data[$column["data"]]=(array_key_exists('source',$column)?$column["source"]:$model[$column["data"]]))@endif


    @endforeach
    var column_data = {!!json_encode($column_data) !!};

    $(function(){
        $('.select2').select2();
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-red',
            radioClass   : 'iradio_minimal-red'
        })


        $('input[type="radio"].minimal.checksl').on('ifToggled', function(){
             var named =$(this).attr('name').replace('_xx','');
             $('[xname="' + $(this).attr('name').replace('_xx','')+'"]').val($(this).val());
             $('[xname="' + $(this).attr('name').replace('_xx','')+'"]').trigger('change');
        });

        $('input[type="checkbox"].minimal.checksl').on('ifToggled', function(){
             var named =$(this).attr('name').replace('_xx','');
             var el = $('input[type="checkbox"][name='+$(this).attr('name')+'].minimal.checksl:checked');
             var pil = [];
             for(var i=0;i<el.length;i++) {
                 pil.push($(el[i]).val());
             }
             $('[xname="' + $(this).attr('name').replace('_xx','')+'"]').val(pil);
             $('[xname="' + $(this).attr('name').replace('_xx','')+'"]').trigger('change');
        });

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').trigger('ifClicked')

        @foreach($form_def["column_def"] as $column)
            @if( $column["type"]=="select" || $column["type"]=="multiple")
                @if( array_key_exists("filterby", $column["options"]))

                        $('[name="{{$column["options"]["filterby"]}}"]').on('change',function(){
                            var el = $(this);
                            //alert($('[name="{{$column["options"]["filterby"]}}"]').val());
                            var named = '{{$column["data"]}}_xx';
                            var named2 ='{{$column["data"]}}';
                            var items = {!!json_encode($column["options"]["items"])!!};
                            //alert($(this).attr('name'))
                            if ($('[name="'+named+'"]').length > 0)
                            {
                                //var del =$('[name="{{$column["data"]}}"]').next()
                                var ele =  $('[name="'+named+'"]');
                                for(var i=0;i<ele.length;i++) {
                                    $(ele[i]).parent().parent().parent().hide()
                                    $(ele[i]).iCheck('uncheck')
                                    for(var j=0;j<items.length;j++)
                                    {
                                        if (($(ele[i]).val()==items[j]["id"]) && (items[j]["filter"]==el.val()))
                                        $(ele[i]).parent().parent().parent().show()
                                    }
                                }

                            }
                            else {
                                if ($('[xname="{{$column["data"]}}"]')[0].tagName == "SELECT")
                                {
                                    $('[xname="{{$column["data"]}}"]').select2('destroy');

                                    var item = [];
                                    if($('[xname="{{$column["data"]}}"]').attr('multiple')==null)
                                    {
                                        item.push('<option value=""> [ -- {{__($column["placeholder"])}} -- ] </option>')
                                    }
                                    for(a in items) {
                                        if (items[a]["filter"]==el.val())
                                            item.push('<option value="'+items[a]["id"]+'">'+items[a]["text"]+'</option>')
                                    }
                                    $('[xname="{{$column["data"]}}"]').html(item.join(''));
                                    $('[xname="{{$column["data"]}}"]').select2();
                                }
                            }
                        });

                @endif

            @endif

        @endforeach
        // first init
        $('.select2').trigger('change');
        $('.cube').each(function(index,elem){
            if($(elem).attr('xname'))
            {
                var item = column_data[$(elem).attr('xname')];
                var opt = $('[name="'+$(elem).attr('xname')+'_xx"]');
                if (opt.length > 0)
                {
                    for(var i=0;i<opt.length;i++)
                    {
                        if (item.indexOf($(opt[i]).val()) > -1)
                        {
                            $(opt[i]).iCheck('check');
                            $(opt[i]).trigger('ifToggled')
                        }
                    }
                }
                else if ($(elem).attr('multiple'))
                {
                    $(elem).val(item);
                    $(elem).trigger('change');
                }
                else if ($(elem)[0].tagName=='SELECT')
                {
                    $(elem).val(item[0]);
                    $(elem).trigger('change');
                }
                // dodol = item;
                // didil = $(elem);
            }
        });

        //$('input.cube').trigger('change');
    })
</script>
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



@section('content')

<div class="row">
    <div class="col-md-8">
      <div class="box box-widget">
        <div class="info-box bg-{{config('global.SITE_SETTING.ADMIN_THEME')}}">
            <span class="info-box-icon">
            <i class="fa fa-clone"></i>
            </span>

            <div class="info-box-content">
            <span class="info-box-text">{{ __($form_def["title"]) }}</span>
            <span class="info-box-number total-rows">{{$model[$form_def["name_column"]]}}</span>

              <div class="progress">
                <div class="progress-bar" id="pbx" style="width: 100%"></div>
              </div>
              <span class="progress-description">
                {{__($form_def["description"])}}
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>

        <div class="box-body">
            @include('gtx::Admin.partials.flash')
            <form method="POST" action="{{ route($form_def["route"]["update"]) }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="token" value="{{$token}}">
                @foreach($form_def["column_def"] as $column)
                    @if($column["type"]!="autonumber")
                    <div class="form-group has-feedback  @error($column["data"]) has-error @enderror">
                        <label for=""> {{ __($column["label"]) }}</label>
                        {{-- @if($column["type"]=="boolean")
                        <!-- radio -->
                       <div class="form-group">
                           @foreach($column["options"]["items"] as $item)
                           <label style="font-weight: normal">
                               <input type="radio" name="{{$column["data"]}}" class="minimal" value="{{$item["id"]}}" {{($model[$column["data"]]==$item["id"])?"checked":""}}> &nbsp; {{$item["text"]}} &nbsp;
                           </label>
                           &nbsp; &nbsp;
                           @endforeach
                       </div>

                       @endif --}}

                        @if($column["type"]=="string")


                            <input type="text" id="{{$column["data"]}}" name="{{$column["data"]}}" value="{{$model[$column["data"]]}}"
                                @if(array_key_exists("rules",$column))
                                    @foreach($column["rules"] as $rule)
                                        {{-- {{$rule}} --}}
                                        @if(strtolower(trim($rule)=="required"))  required @endif
                                        @if(strpos(strtolower(trim($rule)),"max:")>-1)
                                            @if($mv = explode(":",$rule)) @endif
                                            maxlength={{$mv[1]}}
                                        @endif
                                        @if(strpos(strtolower(trim($rule)),"min:")>-1)
                                            @if($mv = explode(":",$rule)) @endif
                                            minlength={{$mv[1]}}
                                        @endif

                                    @endforeach
                                @endif
                            autocomplete="off" autofocus class="form-control cube" placeholder=" {{ __($column["placeholder"]) }}">
                            <span class="glyphicon glyphicon-edit form-control-feedback"></span>
                        @endif
                        @if($column["type"]=="select")
                            @if($column["options"]["type"]=="simple")

                    <input type="hidden" name="{{$column["data"]}}" xname="{{$column["data"]}}" class="form-control cube" value="{{$column["source"][0]}}">
                                <div class="row">
                                    <div class="form-group">
                                        @foreach($column["options"]["items"] as $item)
                                        <div class="col-md-4">
                                            <label style="font-weight: normal">
                                            <input type="radio" name="{{$column["data"]}}_xx" data-filter="{{array_key_exists("filterby",$column["options"])?$column["options"]["filterby"]:""}}" class="minimal checksl" value="{{$item["id"]}}" {{(($item["id"]==$column["source"][0])?"checked":"")}}>  &nbsp; {{$item["text"]}} &nbsp;
                                            </label>
                                        </div>
                                        @endforeach

                                    </div>

                                </div>
                            @endif
                            @if($column["options"]["type"]=="combo")
                                <select id="{{$column["data"]}}" name="{{$column["data"]}}" xname="{{$column["data"]}}" data-filter="{{array_key_exists("filterby",$column["options"])?$column["options"]["filterby"]:""}}" class="form-control input-filter select2 cube" dt-ctx="" placeholder="" style="width:100%">
                                    <option value=""> [ -- {{__($column["placeholder"])}} -- ]</option>
                                    @foreach($column["options"]["items"] as $v)
                                        <option value="{{$v["id"]}}" {{(($v["id"]==$column["source"][0])?"selected":"")}}>{{$v["text"]}}</option>
                                    @endforeach
                                </select>
                            @endif

                        @endif
                        @if($column["type"]=="multiple")
                            @if($column["options"]["type"]=="simple")

                                <select name="{{$column["data"]}}[]"  xname="{{$column["data"]}}" class="form-control cube" multiple style="display:none">
                                    @foreach($column["options"]["items"] as $v)
                                        <option value="{{$v["id"]}}"  {{in_array("default",$v)?($v["default"]?"selected":""):""}}>{{$v["text"]}}</option>
                                    @endforeach
                                </select>
                                <div class="row">
                                    <div class="form-group">
                                    @foreach($column["options"]["items"] as $item)
                                        <div class="col-md-4">
                                            <label style="font-weight: normal">
                                                <input type="checkbox" name="{{$column["data"]}}_xx" data-filter="{{array_key_exists("filterby",$column["options"])?$column["options"]["filterby"]:""}}" class="minimal checksl" value="{{$item["id"]}}" {{in_array($item["id"],$column["source"])?"checked":""}}>  &nbsp; {{$item["text"]}} &nbsp;
                                            </label>
                                        </div>

                                    @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($column["options"]["type"]=="combo")
                                <select id="{{$column["data"]}}" name="{{$column["data"]}}[]"  xname="{{$column["data"]}}" data-filter="{{array_key_exists("filterby",$column["options"])?$column["options"]["filterby"]:""}}" class="form-control input-filter select2 cube" dt-ctx="" placeholder="" style="width:100%" multiple>
                                    @foreach($column["options"]["items"] as $v)
                                        <option value="{{$v["id"]}}" {{in_array($v["id"],$column["source"])?"selected":""}}>{{$v["text"]}}</option>
                                    @endforeach
                                </select>
                            @endif

                        @endif

                        @error($column["data"])
                             <span class="help-block" role="alert">
                                 <strong>{{ __($message) }}</strong>
                             </span>
                         @enderror
                    </div>
                    @endif

                @endforeach
                {{--
                <div class="form-group has-feedback  @error('country_name') has-error @enderror">
                    <label for="exampleInputEmail1">{{__("Nama Negara")}}</label>
                    <input type="text" id="country_name" name="country_name" value="{{$province->province_name}}" required autocomplete="off" autofocus class="form-control" placeholder="{{__("Nama Negara")}}">
                    <span class="glyphicon glyphicon-edit form-control-feedback"></span>
                    @error('country_name')
                         <span class="help-block" role="alert">
                             <strong>{{ __($message) }}</strong>
                         </span>
                     @enderror
                 </div>

                 <div class="form-group has-feedback @error('country_code') has-error @enderror">
                    <label for="exampleInputEmail1">{{__("Kode Negara")}}</label>
                    <input type="text" id="country_code" name="country_code" value="{{$country->country_code}}" required autocomplete="off" autofocus class="form-control" placeholder="{{__("Kode Negara")}}">

                    @error('country_code')
                        <span class="help-block" role="alert">
                            <strong>{{ __($message) }}</strong>
                        </span>
                    @enderror
                </div> --}}





                  <div class="row">
                    <div class="col-xs-3">
                    <a href="{{route($form_def["route"]["browse"]) }}" class="btn btn-success btn-block btn-flat"><i class="fa fa-chevron-left"></i> {{ __('Kembali') }}</a>
                      </div>
                    <div class="col-xs-6">
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-3">
                      <button type="submit" class="btn btn-success btn-block btn-flat"><i class="fa fa-save"></i> {{ __('Update') }}</button>
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
