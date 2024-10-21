<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" style="text-decoration: none" class="brand-link p-2">
        <img src="{{ asset('img/logo_utb.png') }}" alt="UTBLogo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><b>Laraset</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <!-- Menu -->
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <!-- Dropdown -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Tabel Utama
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/asset" class="nav-link">
                                <i class="nav-icon fas fa-circle"></i>
                                <p>Daftar Aset</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/users/admins" class="nav-link">
                                <i class="nav-icon fa-solid fa-user-tie"></i>
                                <p>Daftar Admin</p>
                            </a>
                        </li>
                        @if (auth()->user()->group_id == 2)
                        <li class="nav-item">
                            <a href="/users/students" class="nav-link">
                                <i class="nav-icon fa-solid fa-user"></i>
                                <p>Daftar Mahasiswa</p>
                            </a>
                        </li>
                        @endif
                        
                    </ul>
                </li>

                <!-- Menu -->
                <li class="nav-item">
                    <a href="/loans" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>
                            Daftar Peminjamanku
                        </p>
                    </a>
                </li>

                <!-- Help Dropdown -->
                <hr>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                    <i class="nav-icon fa-solid fa-circle-info"></i>
                        <p>Informasi Pendukung
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('asset.information') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-shapes"></i>
                                <p>Kategori Aset</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.information') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-users"></i>
                                <p>Kategori Pengguna</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('loans.information') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-timeline"></i>
                                <p>Alur Peminjaman</p>
                            </a>
                        </li>

                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
