<nav id="sidebar" aria-label="Main Navigation">
    <div class="content-header">
        <a class="fw-semibold text-dual" href="{{ route("dashboard") }}">
            <span class="smini-hide fs-5 tracking-wider">MAS<span class="fw-normal">BIS</span></span>
        </a> {{-- Logo --}}
        <div>
            <a class="d-lg-none btn btn-sm btn-alt-secondary ms-1" data-toggle="layout"
                data-action="sidebar_close" href="javascript:void(0)">
                <i class="fa fa-fw fa-times"></i>
            </a> {{-- Close Sidebar, Visible only on mobile screens || Layout API, functionality initialized in Template._uiApiLayout() --}}
        </div> {{-- Extra --}}
    </div> {{-- Side Header --}}
    <div class="js-sidebar-scroll">
        <div class="content-side">
            <ul class="nav-main">
                @if(Auth::user()->role != 'admin_sekolah')
                    <li class="nav-main-item">
                        <a class="nav-main-link" href="{{ route("dashboard") }}">
                            <i class="nav-main-link-icon si si-speedometer"></i>
                            <span class="nav-main-link-name">Dashboard</span>
                        </a>
                    </li>
                @endif
                <li class="nav-main-heading">Manajemen Data</li>
                @if(Auth::user()->role == 'super')
                    <li class="nav-main-item">
                        <a class="nav-main-link" href="{{ route("admin") }}">
                            <i class="nav-main-link-icon si si-users"></i>
                            <span class="nav-main-link-name">Admin</span>
                        </a>
                    </li>
                @endif
                @if(Auth::user()->role == 'super' || Auth::user()->role == 'admin')
                    <li class="nav-main-item">
                        <a class="nav-main-link" href="{{ route("bus") }}">
                            <i class="nav-main-link-icon fa fa-bus-simple"></i>
                            <span class="nav-main-link-name">Bis</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link" href="{{ route("sekolah") }}">
                            <i class="nav-main-link-icon fa fa-school"></i>
                            <span class="nav-main-link-name">Sekolah</span>
                        </a>
                    </li>
                @endif
                @if(Auth::user()->role == 'admin_sekolah')
                    <li class="nav-main-item">
                        <a class="nav-main-link" href="{{ route("pelajar") }}">
                            <i class="nav-main-link-icon fa fa-users"></i>
                            <span class="nav-main-link-name">Pelajar</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div> {{-- Side Navigation --}}
    </div> {{-- Sidebar Scrolling --}}
</nav>