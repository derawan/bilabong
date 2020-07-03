<style>
    .user-panel {
        /* background:#ffffff; */
    }

    .user-panel > .image > img {
        border-radius: 5px !important;/*50%;*/
    }

    .user-panel .info p, .user-panel .info a { color:#ffffff !important}
    .sidebar-menu .header {
        color:#ffffff !important;
        background-color:#000 !important;
    }
</style>
<aside class="main-sidebar">
    <section class="sidebar">
      @auth
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{Auth::user()->getAvatar()}}" class="" alt="User Image">
        </div>
        <div class="pull-left info">
            <p>{{Auth::user()->name}}</p>

            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search..." autocomplete="off">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                  <i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header"><i class="fa fa-th"></i> MAIN NAVIGATION</li>


        @if( auth()->user()->can('browse_country') || auth()->user()->can('add_country') || auth()->user()->can('browse_province') || auth()->user()->can('add_province') || auth()->user()->can('browse_city') || auth()->user()->can('add_city')|| auth()->user()->can('browse_type') || auth()->user()->can('add_type') || auth()->user()->can('master_data') )
        <li class="treeview  {{ (in_array(request()->route()->getName(), ['admin.countries','admin.countries.create','admin.provinces','admin.provinces.create','admin.provinces.show','admin.cities','admin.cities.create','admin.cities.show','admin.types','admin.types.create','admin.types.edit','admin.types.show'])) ? ' active menu-open' : '' }}">
            <a href="#">
              <i class="fa fa-clone"></i> <span>Manajemen Data Master</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
                @if( (!auth()->user()->can('browse_country')) && auth()->user()->can('add_country'))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.countries','admin.countries.create','admin.countries.show'])) ? ' active' : '' }}"><a href="{{route('admin.countries.create')}}"><i class="fa fa-circle-o"></i> {{__("Tambah Negara")}}</a></li>
                @endif
                @if( (auth()->user()->can('browse_country')))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.countries','admin.countries.create'])) ? ' active' : '' }}"><a href="{{route('admin.countries')}}"><i class="fa fa-circle-o"></i> {{__("Daftar Negara")}}</a></li>
                @endif

                @if( (!auth()->user()->can('browse_province')) && auth()->user()->can('add_province'))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.provinces','admin.provinces.create','admin.provinces.show'])) ? ' active' : '' }}"><a href="{{route('admin.provinces.create')}}"><i class="fa fa-circle-o"></i> {{__("Tambah Propinsi Baru")}}</a></li>
                @endif
                @if( (auth()->user()->can('browse_province')))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.provinces','admin.provinces.create'])) ? ' active' : '' }}"><a href="{{route('admin.provinces')}}"><i class="fa fa-circle-o"></i> {{__("Daftar Propinsi")}}</a></li>
                @endif

                @if( (!auth()->user()->can('browse_city')) && auth()->user()->can('add_city'))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.cities','admin.cities.create','admin.cities.show'])) ? ' active' : '' }}"><a href="{{route('admin.cities.create')}}"><i class="fa fa-circle-o"></i> {{__("Tambah Kabupaten/Kota Baru")}}</a></li>
                @endif
                @if( (auth()->user()->can('browse_city')))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.cities','admin.cities.create'])) ? ' active' : '' }}"><a href="{{route('admin.cities')}}"><i class="fa fa-circle-o"></i> {{__("Daftar Kabupaten/Kota")}}</a></li>
                @endif

                @if( (!auth()->user()->can('browse_type')) && auth()->user()->can('add_type'))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.types','admin.types.create','admin.types.show'])) ? ' active' : '' }}"><a href="{{route('admin.types.create')}}"><i class="fa fa-circle-o"></i> {{__("Tambah Tipe Kategori")}}</a></li>
                @endif
                @if( (auth()->user()->can('browse_type')))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.types','admin.types.create'])) ? ' active' : '' }}"><a href="{{route('admin.types')}}"><i class="fa fa-circle-o"></i> {{__("Daftar Tipe Kategori")}}</a></li>
                @endif

                @if( (auth()->user()->can('master_data')))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.categories','admin.categories.create'])) ? ' active' : '' }}"><a href="{{route('admin.categories')}}"><i class="fa fa-circle-o"></i> {{__("Daftar Kategori")}}</a></li>
                @endif
                {{-- @if( (auth()->user()->can('master_data')))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.multi-categories','admin.multi-categories.create'])) ? ' active' : '' }}"><a href="{{route('admin.multi-categories')}}"><i class="fa fa-circle-o"></i> {{__("Daftar Kategori Bertingkat")}}</a></li>
                @endif --}}

            </ul>
        </li>
        @endif
        @if( auth()->user()->can('browse_permission') || auth()->user()->can('add_permission') || auth()->user()->can('browse_role') || auth()->user()->can('add_role') || auth()->user()->can('browse_user') || auth()->user()->can('add_user'))
        <li class="treeview  {{ (in_array(request()->route()->getName(), ['admin.users','admin.users.create','admin.users.show','admin.permissions','admin.permissions.create','admin.permissions.edit','admin.roles','admin.roles.create','admin.roles.edit'])) ? ' active menu-open' : '' }}">
            <a href="#">
              <i class="fa fa-users"></i> <span>Admin User Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
                @if( (!auth()->user()->can('browse_user')) && auth()->user()->can('add_user'))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.users','admin.users.create','admin.users.show'])) ? ' active' : '' }}"><a href="{{route('admin.users.create')}}"><i class="fa fa-circle-o"></i> {{__("Create Users")}}</a></li>
                @endif
                @if( (auth()->user()->can('browse_user')))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.users','admin.users.create','admin.users.show'])) ? ' active' : '' }}"><a href="{{route('admin.users')}}"><i class="fa fa-circle-o"></i> {{__("Users")}}</a></li>
                @endif

                @if( (!auth()->user()->can('browse_role')) && auth()->user()->can('add_role'))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.roles','admin.roles.create','admin.roles.edit'])) ? ' active' : '' }}"><a href="{{route('admin.roles.create')}}"><i class="fa fa-circle-o"></i>  {{__("Create Roles")}}</a></li>
                @endif
                @if( (auth()->user()->can('browse_role')))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.roles','admin.roles.create','admin.roles.edit'])) ? ' active' : '' }}"><a href="{{route('admin.roles')}}"><i class="fa fa-circle-o"></i>  {{__("Roles")}}</a></li>
                @endif

                @if( (!auth()->user()->can('browse_permission')) && auth()->user()->can('add_permission'))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.permissions','admin.permissions.create','admin.permissions.edit'])) ? ' active' : '' }}"><a href="{{route('admin.permissions.create')}}"><i class="fa fa-circle-o"></i> {{__("Create Permissions")}}</a></li>
                @endif
                @if( (auth()->user()->can('browse_permission')))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.permissions','admin.permissions.create','admin.permissions.edit'])) ? ' active' : '' }}"><a href="{{route('admin.permissions')}}"><i class="fa fa-circle-o"></i> {{__("Permissions")}}</a></li>
                @endif



            </ul>
        </li>
        @endif
        @if( auth()->user()->can('show_log') || auth()->user()->can('system_setting') || auth()->user()->can('delete_log') || auth()->user()->can('download_log') || auth()->user()->can('file_manager'))
        <li class="treeview  {{ (in_array(request()->route()->getName(), ['admin.filemanager','admin.config.settings','admin.logmanager'])) ? ' active menu-open' : '' }}">
            <a href="#">
              <i class="fa fa-gears"></i> <span>System Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
                @if( (auth()->user()->can('system_setting')))
                <li class="{{ (in_array(request()->route()->getName(), ['admin.config.settings'])) ? ' active' : '' }}"><a href="{{route('admin.config.settings')}}"><i class="fa fa-circle-o"></i> {{__("System Settings")}}</a></li>
                @endif
                @if( (auth()->user()->can('show_log')) && (auth()->user()->can('delete_log') || auth()->user()->can('download_log')) )
                <li class="{{ (in_array(request()->route()->getName(), ['admin.logmanager'])) ? ' active' : '' }}"><a href="{{route('admin.logmanager')}}"><i class="fa fa-circle-o"></i>  {{__("Log Management")}}</a></li>
                @endif

                @if( (auth()->user()->can('file_manager')) )
                <li class="{{ (in_array(request()->route()->getName(), ['admin.filemanager'])) ? ' active' : '' }}"><a href="{{route('admin.filemanager')}}"><i class="fa fa-circle-o"></i> {{__("File Management")}}</a></li>
                @endif



            </ul>
        </li>
        @endif

      </ul>
      @endauth
    </section>
  </aside>
