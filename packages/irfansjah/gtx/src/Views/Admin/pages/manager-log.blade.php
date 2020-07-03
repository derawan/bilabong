@extends("gtx::Admin.layouts.app")
@section('scripts')
<script>
    function DownloadLog() {
        var path ="{{route('admin.logs.download')}}"
        document.location= "{{route('admin.logs.download')}}?q="+SELECTED_LOG.b64;
    }
    function DeleteLog(){
        $.ajax({url:"{{route('admin.logs.delete')}}?q="+SELECTED_LOG.b64,
        success:function() {
            GetLogChannel();
        }, failed:function() {
            GetLogChannel();
        }
        });
    }
    function LoadLog() {
        $('#lgm').block();
        $('#pb').css('width','0%');
        var l = ["Emergency","Alert","Critical","Error","Warning","Notice","Info","Debug"];
        var ly = [];
        $.ajax({
            url: "{{route('admin.logs.load')}}?q=" + SELECTED_LOG.b64,
            success:function(a) {
                try {
                    $('.channel-log-entry').show();
                    $('.log-entries').html(a.length + ' entries');
                    $('.log-entries').attr('title', a.length + ' entries');
                    var item = [];
                    var ctr=0;
                    for(i in a) {
                        var sty = '';
                        switch (a[i].level.toUpperCase()) {
                            case 'ERROR': sty = 'bg-red'; break;
                            case 'WARNING': sty = 'bg-yellow'; break;
                            case 'INFO': sty = 'bg-blue'; break;

                        }
                        if (ly[a[i].level] == null) ly[a[i].level] = 0;
                        ly[a[i].level]++
                        var t1 = '<td><textarea style="display:none">'+a[i].stack+'</textarea><span class="badge '+sty+'"> '+a[i].level.toUpperCase()+'</span></td>'
                        var t2 = '<td><span> '+a[i].message+'</span></td>'
                            t2 += '<td style="width:150px"><span> '+a[i].datetime+'</span></td>'
                            t2 += '<td><span> '+a[i].channels+'</span></td>'
                        item.push('<tr class="lvx">'+t1+t2+'</tr>');
                        ctr++;
                        $('#pb').css('width',(ctr/a.length*100) + '%');
                    }
                    $("#log_ent").html(item.join(''));
                    $('.lvx').on('dblclick',function(){
                        $('#stacktrace_body').html('<pre>'+$(this).find('textarea').val()+'</pre>');
                        $('#stacktrace').modal('show');
                    })

                }
                catch(e){

                }
                $('#lgm').unblock();
            },
            failed:function() {
                $('#lgm').unblock();
            }
        })
    }
    function GetLogChannel(){
        $('.channel-info').hide();
        $('.channel-command').hide();
        $('.channel-log-entry').hide();
        $.ajax({url:"{{route('admin.logs.channels')}}",
        success:function(a){
            var channel = [];
            for(i in a)
            {
                var stri = '<a href="#"><span class="badge badge-secondary"><i class="far fa-hdd"></i> '+i+'</span><span class="label label-primary pull-right">'+a[i].files.length+' Files</span></a>';
                var str  = '<li>';
                    str += stri;
                    if (a[i].files.length > 0)
                    {
                        strj = '<ul class="nav nav-pills nav-stacked" style="padding-left:40px">';
                        strjx = [];
                        for(j in a[i].files)
                        {
                            strjx.push('<li><a href="javascript:;" class="log-select" dta-item="'+i+'"><textarea class="hidden" dta-item="'+i+'" style="display:none">'+JSON.stringify(a[i].files[j])+'</textarea><i class="fa fa-document"></i>'+a[i].files[j].filename+'<span class="label label-success pull-right">'+a[i].files[j].sizex+'</span></a></li>')
                        }
                        strj += strjx.join('')
                        strj +='</ul>';
                        str += strj;
                    }
                    str += '</li>';
                channel.push(str);
            }
            $('.log-channels > ul').html(channel.join(''))
            $('.log-select').on('click',function(){
                $('.log-channels>ul>li>ul>li').removeClass('active')
                $(this).parent().addClass('active');
                $('.channel-info').show();
                $('.channel-log-entry').hide();
                $('.channel-command').show();
                //$('.channel-log-entry').show();
                $('.channel-box').text($(this).find('textarea').attr('dta-item'))
                $('.channel-fname').text(JSON.parse($(this).find('textarea').val())['filename'])
                $('.channel-fsize').text(JSON.parse($(this).find('textarea').val())['sizex'])
                window.SELECTED_LOG = JSON.parse($(this).find('textarea').val());
                $('#pb').css('width','0%');
            })

        }})
    }
    $(function(){
        GetLogChannel();
        $('.btn-load-log').on('click',LoadLog);
        $('.btn-delete-log').on('click',DeleteLog);
        $('.btn-download-log').on('click',DownloadLog);
    })
</script>
@endsection
@section('content')

<div class="box box-widget">


      <div class="info-box bg-{{config('global.SITE_SETTING.ADMIN_THEME')}}" style="margin:0px">
        <span class="info-box-icon"><i class="ion ion-folder"></i></span>

        <div class="info-box-content">
          <span class="info-box-number">{{__("Log Management")}}</span>

          <span class="progress-description">

            {{__("This is where you can manage your logs which are defined on your log system config")}}
              </span>
        </div>
      </div>


    <div class="box-body" id="lgm" style="border-top:1px solid #d2d6de;border-bottom:1px solid #d2d6de;padding-top:0px;padding-bottom:0px;padding-left:0px;padding-right:0px">
        <div class="row" style="">
            <div class="log-channels col-md-4" style="border-right:1px solid #d2d6de;min-height:500px;padding-right:0px;">
                <ul class="nav nav-pills nav-stacked">

                  </ul>
            </div>
            <div class="col-md-8" style="padding-left:0px;">

                <div class="box" style="border-top-color: transparent;border-radius:0px;border-top:none;box-shadow:none">
                    <div class="box-header">
                      <h3 class="box-title">{{__("Log Info")}}</h3>

                      <div class="box-tools pull-right">

                      </div>
                    </div>
                    <div class="info-box bg-aqua channel-info" style="border-radius:0px;margin-bottom:0px;display:none">
                        <span class="info-box-icon"><i class="ion-ios-folder-outline"></i></span>

                        <div class="info-box-content">
                          <span class="info-box-text channel-box">Direct Messages</span>
                          <span class="info-box-number channel-fname">163,921</span>

                          <div class="progress">
                            <div class="progress-bar" id="pb" style="width: 0%"></div>
                          </div>
                          <span class="progress-description channel-fsize">
                                0 B
                              </span>
                        </div>
                        <!-- /.info-box-content -->
                      </div>
                      <div class="box-footer box-comments channel-command" style="padding:5px;display:none">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm btn-download-log"><i class="fas fa-download"></i></button>
                            <button type="button" class="btn btn-default btn-sm btn-delete-log"><i class="fas fa-trash-alt"></i></button>
                        </div>
                        <button type="button" class="btn btn-default btn-sm btn-load-log"><i class="fas fa-eye"></i></button>

                    </div>

                    <div class="box-header channel-log-entry" style="display:none">
                        <h3 class="box-title">Log Entries</h3>

                        <div class="box-tools pull-right">
                          <span data-toggle="tooltip" title="3 New Messages" class="badge bg-light-blue log-entries">0</span>
                        </div>
                      </div>


                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding" style="height: 285px;border-top:1px solid #f4f4f4;border-bottom:1px solid #d2d6de;border-bottom-left-radius:0px;border-bottom-right-radius:0px">
                        <table class="table table-hover table-bordered" >
                            <tbody id="log_ent">

                            </tbody>
                          </table>




                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer hidden">

                    </div>
                    <!-- /.box-footer-->
                  </div>


            </div>
        </div>
    </div>
    <!-- /.box-header -->

    <!-- /.box-body -->
    <!-- /.box-footer -->
    <div class="box-footer bg-{{config('global.SITE_SETTING.ADMIN_THEME')}}">

    </div>
    <!-- /.box-footer -->

<style>
    pre { overflow:visible !important;
        display: block;
        padding: 9.5px;
        margin: 0 0 10px;
        font-size: 13px;
        line-height: 1.42857143;
        color: #333;
        word-break: break-all;
        word-wrap: break-word;
        background-color: transparent;
        border: 1px none #ccc;
        border-radius: 0px;
    }
</style>


    <div class="modal" id="stacktrace">
        <div class="modal-dialog">
          <div class="modal-content" style="width:600px">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><i class="fa fa-clone"></i> Stack Trace</h4>
            </div>
            <div class="modal-body">
              <div class="row" style="max-height:400px;overflow:auto">
                  <div class="col-md-12" id="stacktrace_body">

                  </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    </div>


    @endsection
