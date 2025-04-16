<!-- Sidebar Start -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="#" class="text-nowrap logo-img">
                <img src=" {{ asset ('assets/images/logos/dark-logo.svg') }}" width="180" alt="" />
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>

                <!--Dashboard-->
                <li class="sidebar-item">
                    @if (Auth::check() && Auth::user()->role === 'mahasiswa')
                        <a class="sidebar-link" href="{{ route('mahasiswa.dashboard') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-dashboard"></i>
                            </span>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    @elseif (Auth::check() && Auth::user()->role === 'karyawan' && Auth::user()->karyawan?->jabatan === 'tu')
                        <a class="sidebar-link" href="{{ route('tu.dashboard') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-dashboard"></i>
                            </span>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    @elseif (Auth::check() && Auth::user()->role === 'karyawan' && Auth::user()->karyawan?->jabatan === 'kaprodi')
                        <a class="sidebar-link" href="{{ route('kaprodi.dashboard') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-dashboard"></i>
                            </span>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    @elseif (Auth::check() && Auth::user()->role === 'admin')
                        <a class="sidebar-link" href="{{ route('admin.dashboard') }}" aria-expanded="false">
                            <span>
                                <i class="ti ti-layout-dashboard"></i>
                            </span>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    @endif
                </li>

                @if(Auth::user()->role === 'mahasiswa')
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Manage Surat</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('mahasiswa.surat') }}" aria-expanded="false">
                            <span><i class="ti ti-cards"></i></span>
                            <span class="hide-menu">Daftar Surat</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('mahasiswa.apply') }}" aria-expanded="false">
                            <span><i class="ti ti-file-description"></i></span>
                            <span class="hide-menu">Ajukan Surat</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user() && Auth::user()->role === 'karyawan' && Auth::user()->karyawan?->jabatan === 'kaprodi')
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Manage Surat</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('kaprodi.surat') }}" aria-expanded="false">
                            <span><i class="ti ti-cards"></i></span>
                            <span class="hide-menu">Daftar Surat</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user() && Auth::user()->role === 'karyawan' && Auth::user()->karyawan?->jabatan === 'tu')
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Manage Surat</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('tu.listSurat') }}" aria-expanded="false">
                            <span><i class="ti ti-cards"></i></span>
                            <span class="hide-menu">Upload Surat</span>
                        </a>
                    </li>

                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Manage Akun</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('tu.listMahasiswa') }}" aria-expanded="false">
                            <span><i class="ti ti-cards"></i></span>
                            <span class="hide-menu">Daftar Akun Mahasiswa</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('tu.listKaryawan') }}" aria-expanded="false">
                            <span><i class="ti ti-cards"></i></span>
                            <span class="hide-menu">Daftar Akun Karyawan</span>
                        </a>
                    </li>
                @endif

                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'karyawan' && Auth::user()->karyawan?->jabatan === 'tu')
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Tambah Akun</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('register') }}" aria-expanded="false">
                    <span>
                      <i class="ti ti-user-plus"></i>
                    </span>
                            <span class="hide-menu">Register</span>
                        </a>
                    </li>
                @endif

                @if(Auth::user()->role === 'admin')
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">Manage Akun</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('admin.listMahasiswa') }}" aria-expanded="false">
                            <span><i class="ti ti-cards"></i></span>
                            <span class="hide-menu">Daftar Akun Mahasiswa</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('admin.listKaryawan') }}" aria-expanded="false">
                            <span><i class="ti ti-cards"></i></span>
                            <span class="hide-menu">Daftar Akun Karyawan</span>
                        </a>
                    </li>
                @endif

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Log Out</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('login') }}" aria-expanded="false">
                <span>
                  <i class="ti ti-login"></i>
                </span>
                        <span class="hide-menu">Log Out</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->
