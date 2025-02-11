<!-- Brand Logo -->
<a href="#" class="brand-link">
    <img src="/template/AdminLTE-3.2.0/dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
        class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">dmin Panel</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="/template/AdminLTE-3.2.0/dist/img/no-profile.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block">{{ auth()->user()->username }}</a>
        </div>
    </div>

    <!-- SidebarSearch Form -->
    {{-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div> --}}

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
   with font-awesome or any other icon font library -->
            <li class="nav-header">Data</li>
            <li class="nav-item">
                <a href="/pengirimans" class="nav-link">
                    <i class="nav-icon fas fa-truck"></i>
                    <p>
                        Pengiriman
                    </p>
                </a>
            </li>

            <li class="nav-header">Pengaturan</li>
            <li class="nav-item">
                <a href="/areas" class="nav-link">
                    <i class="nav-icon fas fa-globe"></i>
                    <p>
                        Kota
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/subareas" class="nav-link">
                    <i class="nav-icon fas fa-location-arrow"></i>
                    <p>
                        Kecamatan
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/userapks" class="nav-link">
                    <i class="nav-icon fas fa-mobile"></i>
                    <p>
                        User Aplikasi
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/users" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        User Panel
                    </p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
