<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{config('global.SITE_INFO.SITE_TITLE')}}| Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  @include('gtx::Admin.partials.head')
  <style>
      .widget-user .widget-user-image > img {
            width: 90px;
            height: auto;
            border: 3px solid #00a65a;
    }

    .login-box {
        width:400px;
    }

    body  {
        background: url('{{asset('assets/themes/adminlte/img/background.jpg')}}');background-size:contain
    }

  </style>
</head>
<body class="hold-transition @yield('body-class')">

@yield('content')

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
<script>
  $(function () {
    $('input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
