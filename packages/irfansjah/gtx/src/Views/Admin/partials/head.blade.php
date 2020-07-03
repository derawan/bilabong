<meta name="csrf-token" content="{{ csrf_token() }}">
  {{-- <link rel="stylesheet" href="{{asset('css/app.css')}}"> --}}
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
   <!-- Font Awesome Icons -->
   <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/plugins/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('assets/plugins/Ionicons/css/ionicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2/dist/sweetalert2.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/plugins/toastr-master/build/toastr.css')}}">
  <link rel="stylesheet" href="{{asset('assets/plugins/iCheck/all.css')}}">
  <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
  @yield('plugin_styles')
  <link rel="stylesheet" href="{{asset('assets/plugins/animate.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/themes/adminlte/css/AdminLTE.css')}}">
  <link rel="stylesheet" href="{{asset('assets/themes/adminlte/css/skins/skin-'.config('global.SITE_SETTING.ADMIN_THEME').'.min.css')}}">

  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style>
    .help-block { font-weight:nomal !important; font-size:.85em }

    #navbar-collapse button {
        margin-top:10px
    }
    /* SOME CUSTOM CSS FOR FILE MANAGER*/
    .fm-tree-branch li>p {margin-bottom: .1rem;padding: .4rem .4rem;white-space: nowrap;cursor: pointer;}
    .fm-modal .modal-body {padding:10px;margin-bottom:20px;}

    .fm hr {margin-top: 0px;margin-bottom: 20px;border: 0;border-top: 1px solid #eee;}
    .fm-modal h5 {font-weight:bold;}
    .fm-modal .modal-header {border-bottom-color: transparent;}
    .fm-tree-branch {display: table;width: 100%;padding-left: 1.4rem !important;}
    .fm-grid .fm-grid-item {position: relative;width: 125px;padding: .4rem;margin-bottom: 1rem;margin-right: 1rem;border-radius: 5px;}
    .flex-column {-ms-flex-direction: column!important;flex-direction: column!important;}
    .align-content-start {-ms-flex-line-pack: start!important;align-content: flex-start!important;}
    .flex-wrap {-ms-flex-wrap: wrap!important;flex-wrap: wrap!important;}
    .d-flex {display: -ms-flexbox!important;display: flex!important;}
    .col-auto {-ms-flex: 0 0 auto;flex: 0 0 auto;width: auto;max-width: none;}
    .justify-content-between {-ms-flex-pack: justify!important;justify-content: flex-end !important;}
    .fm .modal-header .close {margin-top: -28px;}
    .fm .d-block {margin:5px;}
    .fm dl.row {margin-left:8px;}
    .row.justify-content-between{margin-bottom:20px;display:flex;}
    .fm .badge { padding:10px;}
    .fm .breadcrumb-item .badge { padding:3px 7px; border-radius: 5px; margin-bottom:2px }
    .fm-breadcrumb .breadcrumb.active-manager {
        margin-top:5px;
        border-top:1px solid #d2d6de;
        border-bottom:1px solid #d2d6de;
        border-left:1px solid #d2d6de;
        border-right:1px solid #d2d6de;
        border-radius:5px;
        padding:3px;
        background-color:#f7f7f7 /*transparent*/;
    }

    .fm .badge {
        background-color:#00a65a;
    }
    .fm .fm-body {
        padding-top:0px;
        padding-bottom:0px;
    }
    .fm-tree, .fm-content {
    padding-top:1rem;
    min-height: 400px;
}
.fm-tree .fm-tree-disk {
    border-bottom:1px solid #d2d6de
}
.fm .fm-body, .fm .fm-tree, .fm .fm-info-block {
    border-color:#d2d6de;
}
.fm .fm-navbar .row.justify-content-between {
    margin-top: 5px;
    margin-bottom: 5px;
    justify-content: flex-start !important;
}
.fm-modal .modal-body {
    padding: 10px;
    margin-bottom: 20px;

}
.fm-additions-file-list .justify-content-between{
    justify-content: initial !important;
}
.box.box-widget > .info-box {
    border-bottom-left-radius: 0px;
    border-bottom-right-radius: 0px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}
div.dataTables_wrapper div.dataTables_paginate {
    margin: 0;
    white-space: nowrap;
    text-align:left;
}
.dataTables_paginate {
    padding: 5px;
}
</style>
<style>
    .content-wrapper > .alert, .content-wrapper > .callout{
    border-radius: 0px;
    margin: 0 0 0 0;
    }

    section.content-header {

        background:#ffffff !important;
        padding:10px 10px 20px 10px !important;
        border-bottom:1px solid #d2d6de;
    }
    .content-wrapper {
        /* //background-color:#ffffff; */
    }
    .content-wrapper > .box, .content-wrapper > .box > .box-footer {
        border-radius:0px !important;
    }
    .content-wrapper > .box.widget-user > .widget-user-header {border-radius:0px;}
</style>
  {{-- Bootstrap 4 setting to compatible with Bootstrap 3 --}}
  {{-- <style>
      .navbar {
    position: relative;
    min-height: 50px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    display: block;
    flex-wrap: nowrap;
    align-items: initial;
    justify-content: initial;
    padding: 0rem;
}

.main-header .sidebar-toggle {
    float: left;
    background-color: transparent;
    background-image: none;
    padding: 13px 13px; /* bagian ini dikurang dua dari 15 15*/
    font-family: fontAwesome;
}

.img-circle {
    border-radius: 50%;
}
.nav {
    padding-left: 0;
    margin-bottom: 0;
    list-style: none;

    /* display: initial; */
    flex-wrap: initial;

}

.nav-stacked > li > a {
    border-radius: 0;
    border-top: 0;
    border-left: 3px solid transparent;
    color: #444;
}
.nav>li>a {
    position: relative;
    display: block;
    padding: 10px 15px;
}

.carousel-inner>.item>a>img, .carousel-inner>.item>img, .img-responsive, .thumbnail a>img, .thumbnail>img {
    display: block;
    max-width: 100%;
    height: auto;
}

.nav-tabs>li {
    float: left;
    /* margin-bottom: -1px; */
}

.open>.dropdown-menu {
    display: block;
}
.dropdown-menu>li>a {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: 400;
    line-height: 1.42857143;
    color: #333;
    white-space: nowrap;
}

.nav>li {
    position: relative;
    display: block;
}

.pull-right {
    float: right!important;
}

.pull-left {
    float: left!important;
}

.progress {
    margin-bottom:20px;
    display: flex;
    /* height: 1rem; */
    overflow: hidden;
    line-height: 0;
    font-size: 0.675rem;
    background-color: #e9ecef;
    border-radius: 0.25rem;
}
      /* .navbar { padding: 0rem 0rem;} */
      /* .main-header .sidebar-toggle {padding: 12px 12px;font-family: fontAwesome;} */
  </style> --}}
 @yield('styles')
