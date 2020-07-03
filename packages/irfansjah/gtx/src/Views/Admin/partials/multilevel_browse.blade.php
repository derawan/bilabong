@extends("gtx::Admin.layouts.app")

@section('plugin_styles')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/iCheck/all.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/ludo-jquery-treetable/css/jquery.treetable.css')}}" />
<link rel="stylesheet" href="{{asset('assets/plugins/ludo-jquery-treetable/css/jquery.treetable.theme.default.css')}}" />

@endsection

@section('plugin_scripts')
<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-fixedcolumns/js/fixedColumns.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{asset('assets/plugins/ludo-jquery-treetable/jquery.treetable.js')}}"></script>
@endsection

@section('styles')
<style>
  .dataTables_paginate  { padding:2px }
  .toolbar {padding:5px; padding-left:20px}
  .dt-table-header .table-filter {
    padding:0px;
  }

  table:focused {
      border:none;
  }
  tr.selected {
      background-color:rgba(0,0,0,.3) !important
  }
</style>
@endsection

@section('top-header')
@endsection

@section('scripts')
<script>
    var MainTable = function() {
        return {
            column_def : [@foreach($table_def["column_def"] as $column)
             {
                 data:'{{$column["data"]}}',
                 name:'{{$column["data"]}}',
                 orderable:{!!$column["orderable"]?"true":"false"!!},
                 searchable:{!!$column["searchable"]?"true":"false"!!},
                 visible:{!!$column["visible"]?"true":"false"!!},
                 @if(array_key_exists("renderer",$column))
                    render:function(data,type,row,meta){
                        return {!!$column["renderer"]!!};
                    },
                 @endif
            },

            @endforeach],
            table_id :'#{{$table_def["table_id"]}}',
            route:{
                @foreach($table_def["route"] as $key => $value)
                {{$key}} : '{{$value}}',
                @endforeach
            },
            SelectedId:null,
            ConfirmTemplate: function() {
                var tpl = '<div class="box" style="border:1px solid #f39c12">' +
                                        '<div class="box-footer no-padding">' +
                                            '<ul class="nav nav-stacked">' +
                                                '<li style="text-align:left"><a href="#">Name : :name</a></li>' +
                                            '</ul>' +
                                        '</div>' +
                          '</div>';
                @if(array_key_exists("template",$table_def))

                    tpl = "" + {!!$table_def["template"]["confirm_template"]!!};

                @endif
                for(a in MainTable.SelectedId) {
                    tpl = tpl.replace(':'+a, MainTable.SelectedId[a])
                }
                return tpl;
            },
            DeleteData:function() {
                @if(in_array("delete",$table_def["commands"]))
                if (MainTable.SelectedId != null) {
                    var html = [];
                        var tpl = MainTable.ConfirmTemplate();

                        html.push(tpl);
                    Swal.queue([{
                        title: '<span>{{__("HAPUS?")}}</span>',
                        html: html.join("") + '<span>{{__("Anda tidak akan dapat mengembalikan data ini lagi setelah dihapus!")}}</span> ',
                        showCancelButton: true,
                        cancelButtonColor: '#d33',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: '{{__("Ya Hapus Sekarang!")}}',
                        showLoaderOnConfirm: true,
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        },
                        preConfirm: () => {
                            return fetch(MainTable.route.delete+'?q='+MainTable.SelectedId.xid)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success)
                                {
                                    $('#{{$table_def["table_id"]}}').DataTable().ajax.reload();
                                }
                                else {
                                    Swal.insertQueueStep({
                                        icon: 'error',
                                        title: '{{$table_def["crud_label"]["delete"]["failed"]}}',
                                        text: data.message
                                    });
                                }
                            })
                            .catch(() => {
                                Swal.insertQueueStep({
                                icon: 'error',
                                title: '{{$table_def["crud_label"]["delete"]["failed"]}}'
                                })
                            })
                        }
                    }])
                    return ;
                }
                @endif
            },
            EditData:function(data) {
                @if(in_array("edit",$table_def["commands"]))
                if (MainTable.SelectedId == null) return;
                Swal.fire({
                    title:  '<span>{{__("PERBAHARUI?")}}</span>',
                    html: MainTable.ConfirmTemplate() + '<strong>Lanjutkan perubahan data pengguna berikut!</strong> ',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: '{{__("Ya Lakukan Perubahan!")}}',
                    showLoaderOnConfirm: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then((result) => {
                    if (result.value) {
                        document.location = MainTable.route.edit+'?id='+ MainTable.SelectedId.xid;
                    }
                    })
                return ;
                @endif
            },
            ShowData:function(data) {
                @if(in_array("show",$table_def["commands"]))
                if (MainTable.SelectedId == null) return;
                Swal.fire({
                    title:  '<span>{{__("LIHAT DETAIL?")}}</span>',
                    html: MainTable.ConfirmTemplate() + '<strong>Lanjutkan Lihat Detail!</strong> ',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: '{{__("Ya Lanjutkan!")}}',
                    showLoaderOnConfirm: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then((result) => {
                    if (result.value) {
                        document.location = MainTable.route.show+'?id='+ MainTable.SelectedId.xid;
                    }
                    })
                return ;
                @endif
            },
            ApproveData:function(data) {
                @if(in_array("approve",$table_def["commands"]))
                if (MainTable.SelectedId == null) return;
                Swal.fire({
                    title:  '<span>{{__("LAKUKAN APPROVAL?")}}</span>',
                    html: MainTable.ConfirmTemplate() + '<strong>Lanjutkan Lihat Detail!</strong> ',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: '{{__("Ya Lanjutkan!")}}',
                    showLoaderOnConfirm: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then((result) => {
                    if (result.value) {
                        document.location = MainTable.route.approve+'?id='+ MainTable.SelectedId.xid;
                    }
                    })
                return ;
                @endif
            },
            RejectData:function(data) {
                @if(in_array("approve",$table_def["commands"]))
                if (MainTable.SelectedId == null) return;
                Swal.fire({
                    title:  '<span>{{__("LAKUKAN REJECT?")}}</span>',
                    html: MainTable.ConfirmTemplate() + '<strong>Lanjutkan Lihat Detail!</strong> ',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: '{{__("Ya Lanjutkan!")}}',
                    showLoaderOnConfirm: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then((result) => {
                    if (result.value) {
                        document.location = MainTable.route.reject+'?id='+ MainTable.SelectedId.xid;
                    }
                    })
                return ;
                @endif
            },
            InitTable:function() {
                var tabel  = ($(this.table_id)).DataTable({
                    fixedHeader: true,
                    processing: true,
                    serverSide: true,
                    responsive:true,
                    drawCallback: function( settings ) {},
                    rowCallback: function( row, data , displayNum, displayIndex, dataIndex) {
                        $(row).attr('dta-index', displayIndex)
                        var tb = $('#{{$table_def["table_id"]}}').DataTable();
                         $(row).attr('dta-page', (tb.page.info().page+1))
                        $(row).attr('dta-pages', tb.page.info().pages)
                        $(row).attr('dta-item', JSON.stringify(data));
                    },
                    dom: 'tp',
                    ajax: this.route.fetch,
                    columns: this.column_def});
                $('#{{$table_def["table_id"]}}').on( 'xhr.dt', function (e, settings, json, xhr) {
                    var tb = $('#{{$table_def["table_id"]}}').DataTable();
                    $('.total-rows').html(json.recordsTotal + ' records :: Hasil Filter ' + json.recordsFiltered + ' records :: Halaman #' + (tb.page.info().page+1));
                    $('#pbx').css('width', ((tb.page.info().page+1)/tb.page.info().pages * 100) + '%')
                } );
                $('#{{$table_def["table_id"]}}').on( 'page.dt', function () {
                    var tb = $('#{{$table_def["table_id"]}}').DataTable();
                    $('#pbx').css('width', ((tb.page.info().page+1)/tb.page.info().pages * 100) + '%')

                } );

                $('#{{$table_def["table_id"]}} tbody').on( 'click','tr', function () {
                        if ( $(this).hasClass('selected') ) {$(this).removeClass('selected');}else {$('tr.selected').removeClass('selected');$(this).addClass('selected');}
                        if ( $(this).hasClass('selected') ) {
                            MainTable.SelectedId = JSON.parse($(this).attr('dta-item'));
                        } else { MainTable.SelectedId = null }
                } );

                $('#{{$table_def["table_id"]}} tbody').attr('tabindex',1).bind( 'keydown', function (e) {
                    var selected = $('tr.selected');
                    var index = $(selected).attr('dta-index');

                    selected.removeClass('selected');
                    switch(e.keyCode) {
                        case 39 : // right
                            index= (index==null)?0:index;
                            var tb = $('#{{$table_def["table_id"]}}').DataTable();
                            if ((tb.page.info().page+1) < tb.page.info().pages)
                                tb.page(tb.page.info().page+1).draw('page')
                            break;
                        case 37 : // left
                            index= (index==null)?0:index;
                            var tb = $('#{{$table_def["table_id"]}}').DataTable();
                            if ((tb.page.info().page-1) > -1)
                            tb.page(tb.page.info().page-1).draw('page')
                            break;
                        case 38 : // up
                            index= (index==null)?10:index;
                            if (index>0)
                                index--;
                            break;
                        case 40 : // down
                            index= (index==null)?-1:index;
                            if (index<9)
                            index++;
                            break;
                    }
                    $('tr[dta-index="'+index+'"]').addClass('selected');
                    MainTable.SelectedId = JSON.parse($('tr.selected').attr('dta-item'));

                } );

                $('#{{$table_def["table_id"]}}').on( 'draw.dt', function () {
                    var tb = $('#{{$table_def["table_id"]}}').DataTable();
                    $('#pbx').css('width', ((tb.page.info().page+1)/tb.page.info().pages * 100) + '%')
                    // disable select text on any table-row
                    $('tr').attr('unselectable','on')
                        .css({'-moz-user-select':'-moz-none',
                            '-moz-user-select':'none',
                            '-o-user-select':'none',
                            '-khtml-user-select':'none',
                            '-webkit-user-select':'none',
                            '-ms-user-select':'none',
                            'user-select':'none'
                        }).bind('selectstart', function(){ return false; });
                } );

                $('.btn-delete, .btn-edit, .btn-show, .btn-approve, .btn-reject').on('click', function(){
                    if ($('tr.selected').length==0)
                    {
                        alert('Silahkan Pilih Dulu')
                    }
                    else {
                        if ($(this).hasClass('btn-delete')) {MainTable.DeleteData()}
                        if ($(this).hasClass('btn-edit')) {MainTable.EditData()}
                        if ($(this).hasClass('btn-approve')) {MainTable.ApproveData()}
                        if ($(this).hasClass('btn-reject')) {MainTable.RejectData()}
                        if ($(this).hasClass('btn-show')) {MainTable.ShowData()}

                    }
                })

                $('.btn-filter').on('click',function(){
                    if($(this).hasClass('btn-info'))
                    {
                        $(this).removeClass('btn-info')
                        $(this).addClass('btn-default')
                        $('.col-filter').addClass('hidden')
                        $('.col-data').addClass('col-md-12')
                        $('.col-data').removeClass('col-md-9')
                    }
                    else {
                        $(this).addClass('btn-info')
                        $(this).removeClass('btn-default')
                        $('.col-filter').removeClass('hidden')
                        $('.col-data').addClass('col-md-9')
                        $('.col-data').removeClass('col-md-12')
                    }
                })

            },
            InitFilter:function(){
                $('.input-filter').each(function(idx, el){
                    $(el).on('change',function(){

                        MainTable.SearchOnColumn();
                    })
                    $(el).on('keyup',function(){
                        MainTable.SearchOnColumn();
                    })
                    $(el).on('keydown',function(){
                        MainTable.SearchOnColumn();
                    })
                 });
                $('.input-vis').each(function(idx,el){
                    $(el).on('click',function(el){
                        MainTable.ToggleColumnVisibility($(this).attr('dta-vis'));
                        if($($(this).children()[0]).hasClass('fa-eye')) {
                            $($(this).children()[0]).removeClass('fa-eye')
                            $($(this).children()[0]).addClass('fa-eye-slash')
                        }
                        else {
                            $($(this).children()[0]).removeClass('fa-eye-slash')
                            $($(this).children()[0]).addClass('fa-eye')
                        }
                        for(a in this.column_def)
                        {
                            $(this.table_id).DataTable().columns(a).adjust();
                        }
                    })
                })
            },
            SearchOnColumn:function() {
                for(a in this.column_def)
                {
                    $(this.table_id).DataTable().columns(a).search($('.input-filter[dta-filter="'+this.column_def[a].name+'"]').val(),false,false,true);
                }
                $(this.table_id).DataTable().columns().draw();
            },
            ToggleColumnVisibility:function(name){
                for(a in this.column_def)
                {
                    if (this.column_def[a].name==name) {
                        var tb = $(this.table_id).DataTable().columns(a).visible()[0];
                        $(this.table_id).DataTable().columns(a).visible(!tb);
                    }
                }
            }
        }
    }()

    $(function () {

        MainTable.InitTable();
        MainTable.InitFilter();

        $('.select2').select2();
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })

    $('input[type="checkbox"].minimal.checksl, input[type="radio"].minimal.checksl').on('ifToggled', function(){
        $('[dta-filter="' + $(this).attr('name').replace('_xx','')+'"]').val($(this).val());
            $('[dta-filter="' + $(this).attr('name').replace('_xx','')+'"]').trigger('change');
    })

        $('.checksl').on('change',function(){
            $('[dta-filter="' + $(this).attr('name').replace('_xx','')+'"]').val($(this).val());
            $('[dta-filter="' + $(this).attr('name').replace('_xx','')+'"]').trigger('change');
        })



        @if(in_array("add",$table_def["commands"]))
                    @if(array_key_exists("create",$table_def["route"]))
                    $('.btn-create').on('click',function(){
            document.location = '{{$table_def["route"]["create"]}}?act={{$akey}}'
        });
                    @endif
                @endif


                $("#example-advanced").treetable({ expandable: true });
// Highlight selected row
                // $("#example-advanced tbody").on("mousedown", "tr", function() {
                //         $(".selected").not(this).removeClass("selected");
                //         $(this).toggleClass("selected");
                //     });

      // Drag & Drop Example Code
    //   $("#example-advanced .file, #example-advanced .folder").draggable({
    //     helper: "clone",
    //     opacity: .75,
    //     refreshPositions: true, // Performance?
    //     revert: "invalid",
    //     revertDuration: 300,
    //     scroll: true
    //   });

    //   $("#example-advanced .folder").each(function() {
    //     $(this).parents("#example-advanced tr").droppable({
    //       accept: ".file, .folder",
    //       drop: function(e, ui) {
    //         var droppedEl = ui.draggable.parents("tr");
    //         $("#example-advanced").treetable("move", droppedEl.data("ttId"), $(this).data("ttId"));
    //       },
    //       hoverClass: "accept",
    //       over: function(e, ui) {
    //         var droppedEl = ui.draggable.parents("tr");
    //         if(this != droppedEl[0] && !$(this).is(".expanded")) {
    //           $("#example-advanced").treetable("expandNode", $(this).data("ttId"));
    //         }
    //       }
    //     });
    //   });

    //   $("form#reveal").submit(function() {
    //     var nodeId = $("#revealNodeId").val()

    //     try {
    //       $("#example-advanced").treetable("reveal", nodeId);
    //     }
    //     catch(error) {
    //       alert(error.message);
    //     }

    //     return false;
    //   });
    });
  </script>
@endsection

@section('content')


<div class="row">
    <div class="col-md-3 col-filter">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab"><i class="fa fa-filter"></i> {{__("Search Filter")}}</a></li>
              <li><a href="#tab_2" data-toggle="tab"><i class="fa fa-eye"></i>  {{__("Column Visibility")}}</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                @foreach($table_def["column_def"] as $column)
                  @if($column["searchable"]==true)
                        <div class="form-group">
                        <label>{{__($column["label"])}}</label>
                        @if($column["type"]=="boolean")
                         <!-- radio -->
                        <div class="form-group">
                            <label style="font-weight: normal">
                                <input type="hidden" dta-filter="{{$column["data"]}}" class="form-control input-filter" dt-ctx="" placeholder="">
                                <input type="radio" name="{{$column["data"]}}_xx" class="minimal checksl" value="" checked>  &nbsp; All &nbsp;
                            </label>
                            @foreach($column["options"]["items"] as $item)
                            <label style="font-weight: normal">
                                <input type="radio" name="{{$column["data"]}}_xx" class="minimal checksl" value="{{$item["id"]}}">  &nbsp; {{$item["text"]}} &nbsp;
                            </label>
                            &nbsp; &nbsp;
                            @endforeach
                        </div>

                        @endif
                        @if($column["type"]=="string")
                        <input type="text" dta-filter="{{$column["data"]}}" class="form-control input-filter" dt-ctx="" placeholder="">
                        @endif
                        @if($column["type"]=="select")
                        <select dta-filter="{{$column["data"]}}" class="form-control input-filter select2" dt-ctx="" placeholder="" style="width:100%">
                        <option value="">[{{__("Please Select")}}]</option>
                            @if($column["options"]["type"]=="simple")
                                @foreach($column["options"]["items"] as $v)
                                    <option value="{{$v["text"]}}">{{$v["text"]}}</option>
                                @endforeach
                            @endif
                        </select>
                        @endif
                        @if($column["type"]=="list")
                        <ul class="nav nav-pills nav-stacked">
                        @foreach($column["options"]["items"] as $v)
                        <li class="{{$akey==$v["id"]?"active":""}}">
                            <a href="{{route('admin.multi-categories')}}?act={{$v["id"]}}">
                                    <i class="fa fa-inbox"></i> {{$v["text"]}}
                                </a>
                            </li>

                        @endforeach
                        </ul>
                        @endif
                        </div>
                  @endif
                @endforeach
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <ul class="nav nav-pills nav-stacked">
                    @foreach($table_def["column_def"] as $column)
                        <li><a href="javascript:;" dta-vis="{{$column["data"]}}" class="input-vis"><i class="fa fa-{{$column["visible"]==true?"eye":"eye-slash"}}"></i> {{$column["label"]}}</a></li>
                    @endforeach
                  </ul>
              </div>
            </div>
          </div>
    </div>


    <div class="col-md-9 col-data">
      <div class="box box-widget">
        <div class="info-box bg-{{config('global.SITE_SETTING.ADMIN_THEME')}}">
            <span class="info-box-icon"><i class="{{$table_def["icon"]}}"></i></span>

            <div class="info-box-content">

              <span class="info-box-text">{{$table_def["description"]}}</span>
              <span class="info-box-number" >{{$table_def["title"]}}</span>

              <div class="progress">
                <div class="progress-bar" id="pbx" style="width: 20%"></div>
              </div>
              <span class="progress-description total-rows"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <div class="mailbox-controls">
                @if(in_array("add",$table_def["commands"]))
                <button type="button" class="btn btn-default btn-sm btn-create"><i class="fa fa-file"></i></button>
                @endif
                <div class="btn-group">
                    @if(in_array("delete",$table_def["commands"]))
                    <button type="button" class="btn btn-default btn-sm btn-delete"><i class="fa fa-trash-o"></i></button>
                    @endif
                    @if(in_array("edit",$table_def["commands"]))
                    <button type="button" class="btn btn-default btn-sm btn-edit"><i class="fa fa-edit"></i></button>
                    @endif
                    @if(in_array("show",$table_def["commands"]))
                    <button type="button" class="btn btn-default btn-sm btn-show"><i class="fa fa-eye"></i></button>
                    @endif
                </div>
                <div class="btn-group">
                    @if(in_array("download",$table_def["commands"]))
                    <button type="button" class="btn btn-default btn-sm btn-download"><i class="fa fa-download"></i></button>
                    @endif
                    @if(in_array("upload",$table_def["commands"]))
                    <button type="button" class="btn btn-default btn-sm btn-upload"><i class="fa fa-upload"></i></button>
                    @endif
                </div>
                <div class="btn-group">
                    @if(in_array("approve",$table_def["commands"]))
                    <button type="button" class="btn btn-default btn-sm btn-approve"><i class="fa fa-check"></i></button>
                    @endif
                    @if(in_array("reject",$table_def["commands"]))
                    <button type="button" class="btn btn-default btn-sm btn-reject"><i class="fa fa-ban"></i></button>
                    @endif
                </div>
                <button type="button" class="btn btn-info btn-sm btn-filter"><i class="fa fa-filter"></i></button>
              </div>
        <div class="box-body">
            @include('gtx::Admin.partials.flash')
            <div class="row" style="padding:top:0px">
                <div class="col-md-12 table-responsive">
                    <table id="example-advanced" class="table table-bordered table-hover">
        <caption>
          <a href="#" onclick="jQuery('#example-advanced').treetable('expandAll'); return false;">Expand all</a>
          <a href="#" onclick="jQuery('#example-advanced').treetable('collapseAll'); return false;">Collapse all</a>
        </caption>
        <thead>
          <tr>
            <th>Name</th>
            <th>Kind</th>
          </tr>
        </thead>
        <tbody>
            {!!$list!!}
        </tbody>
      </table>

                </div>
            </div>

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
