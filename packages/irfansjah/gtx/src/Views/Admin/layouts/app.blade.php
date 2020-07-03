<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{config('global.SITE_INFO.SITE_TITLE')}} | Dashboard</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   @include('gtx::Admin.partials.head')

</head>
<body class="hold-transition skin-{{config('global.SITE_SETTING.ADMIN_THEME')}} sidebar-mini fixed">
<div class="wrapper">
    @include('gtx::Admin.partials.header')
    @include('gtx::Admin.partials.main-sidebar')
    <div class="content-wrapper">
        <div style="display:none; padding: 20px 30px; background: rgb(243, 156, 18); z-index: 999999; font-size: 16px; font-weight: 600;">
            <a class="float-right" href="#" data-toggle="tooltip" data-placement="left" title="Never show me this again!" style="color: rgb(255, 255, 255); font-size: 20px;">×</a><a href="https://dashboardpack.com/" style="color: rgba(255, 255, 255, 0.9); display: inline-block; margin-right: 10px; text-decoration: none;">Looking for an admin dashboard template based on Bootstrap 4, Vue, React or Angular? Look no further!</a><a class="btn btn-default btn-sm" href="https://dashboardpack.com/" style="margin-top: -5px; border: 0px; box-shadow: none; color: rgb(243, 156, 18); font-weight: 600; background: rgb(255, 255, 255);">Find out More!</a>
        </div>

        <section class="content-header bg-white hidden">
        <h1><i class="fa fa-clone"></i> Dashboard<small>Version 2.0</small></h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
        </section>

        <section class="content">


            @yield('content')
        </section>
    </div>

  @include('gtx::Admin.partials.footer')
  @include('gtx::Admin.partials.control-sidebar')



</div>

@include('gtx::Admin.partials.scripts')
<script>
    window.HELPERS = function() {
        return {
            FUNC_BOOTSTRAP_VERSION : function() { return jQuery.fn.tooltip.Constructor.VERSION},
            FUNC_JQUERY_VERSION : function() { return jQuery.fn.jquery},
            FUNC_JQUERY_UI_VERSION : function() {var version = jQuery.ui ? jQuery.ui.version || "pre 1.6" : 'jQuery-UI not detected'; return version; },
            FUNC_BLOCK_AREA:function(ar){
                if (ar == 1 || ar == 3 || ar == 5 || ar == 7)
                    $('.navbar').block({message:''});
                if (ar == 2 || ar == 3 || ar == 6 || ar == 7)
                    $('.main-sidebar').block({message:''});
                if (ar == 4 || ar == 5 || ar == 6 || ar == 7)
                    $('.content-wrapper').block({message:''});
            },
            FUNC_UNBLOCK_AREA:function(ar){
                if (ar == 1 || ar == 3 || ar == 5 || ar == 7)
                    $('.navbar').unblock();
                if (ar == 2 || ar == 3 || ar == 6 || ar == 7)
                    $('.main-sidebar').unblock();
                if (ar == 4 || ar == 5 || ar == 6 || ar == 7)
                    $('.content-wrapper').unblock();
            },
            FUNC_ALERT:function(type,title,content, el) {
                var dvc = $('<div class="alert alert-dismissible"></div>');
                var ic  = "";var tipe = "";
                switch (type) {
                    case "danger":
                    case "error" :
                        tipe = "danger";
                        ic = "fa fa-ban";
                        break;
                    case "info" :
                        tipe = "info";
                        ic = "fa fa-info";
                        break;
                    case "warning" :
                        tipe = "warning";
                        ic = "fa fa-warning";
                        break;
                    case "success" :
                        tipe = "success";
                        ic = "fa fa-check";
                        break;
                }
                dvc.addClass('alert-'+tipe)
                var dvb = $('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>');
                var dvt = $('<h4><i class="'+ic+'"></i> &nbsp; '+title+'</h4>')
                var dvi = $('<div></div>'); dvi.html(content);
                dvc.append(dvb);dvc.append(dvt);dvi.insertAfter(dvt);
                if (el==null)
                    dvc.insertAfter($('.content-header'));
                else dvc.appendTo($(el));


            }
        }
    }()

</script>
</body>
</html>
