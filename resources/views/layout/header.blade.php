<header id="page-header">
    <div class="content-header">
        <div class="d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle"><i class="fa fa-fw fa-bars"></i></button> {{-- Toggle Sidebar || Layout API, functionality initialized in Template._uiApiLayout() --}}
                <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-none d-lg-inline-block" data-toggle="layout" data-action="sidebar_mini_toggle"><i class="fa fa-fw fa-ellipsis-v"></i></button> {{-- Toggle Mini Sidebar || Layout API, functionality initialized in Template._uiApiLayout() --}}
        </div> {{-- Left Section --}}
        <div class="d-flex align-items-center">
            <div class="dropdown d-inline-block ms-2">
                <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center"
                    id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <img class="rounded-circle" src="{{ asset("media/photos/icon-bus4.png") }}" alt="Header Avatar"
                        style="width: 21px;">
                    <span class="d-none d-sm-inline-block ms-2">{{ Auth::user()->nama }}</span>
                    <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block opacity-50 ms-1 mt-1"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0">
                    <div class="p-2">
                        <a class="dropdown-item d-flex align-items-center justify-content-between"
                            href="{{ route("user.viewUbahPassword") }}">
                            <span class="fs-sm fw-medium">Ubah Password</span>
                        </a>
                    </div>
                    <div role="separator" class="dropdown-divider m-0"></div>
                    <div class="p-2">
                        <a class="dropdown-item d-flex align-items-center justify-content-between"
                            href="{{ route("logoutaksi") }}">
                            <span class="fs-sm fw-medium">Keluar</span>
                        </a>
                    </div>
                </div> {{-- dropdown-menu --}}
            </div> {{-- User Dropdown --}}
        </div> {{-- Right Section --}}
    </div> {{-- Header Content --}}
    <div id="page-header-loader" class="overlay-header bg-body-extra-light">
        <div class="content-header">
            <div class="w-100 text-center">
                <i class="fa fa-fw fa-circle-notch fa-spin"></i>
            </div>
        </div>
    </div> {{-- Header Loader || Please check out the Loaders page under Components category to see examples of showing/hiding it --}}
</header>