
<!-- jQuery 3 -->
<script src="{{asset('assets/plugins/jquery/dist/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4  jangan pake ini bila  gunakan bootstrap-slider-->
{{----}}
<script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

<!-- Bootstrap 3.3.7 -->
<script src="{{asset('assets/plugins/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/plugins/sweetalert2/dist/sweetalert2.min.js')}}"></script>
<script src="{{asset('assets/plugins/toastr-master/build/toastr.min.js')}}"></script>
<script src="{{asset('assets/plugins/fastclick/lib/fastclick.js')}}"></script>
<script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}"></script>

<!-- AdminLTE App -->
<script src="{{asset('assets/themes/adminlte/js/adminlte.min.js')}}"></script>
@yield('plugin_scripts')
<!-- SlimScroll -->
<script src="{{asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('assets/plugins/jquery.blockUI.js')}}"></script>

<script>
    Toast = Swal;
    function Make_Toast(icon,title,text,footer)
    {
        Swal.fire({
        icon: icon,
        title: title,
        text: text,
        footer: footer
        })
    }
</script>
@yield('scripts')


