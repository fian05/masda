<header id="page-header">
    <div class="content-header">
        <div class="d-flex align-items-center">
            <a class="fw-bold fs-lg tracking-wider text-dual me-2" role="button" href="{{ route('beranda') }}">
                MAS<span class="fw-semibold">DA</span>
            </a>
        </div>
        <div class="d-flex align-items-center">
            @if (isset(Auth::user()->nama))
                <a class="btn btn-success" href="dashboard">
                    <i class="fa fa-fw fa-arrow-right-to-bracket opacity-50"></i>
                    <span class="d-none d-sm-inline-block ms-1">Dashbord</span>
                </a>
            @else
                <a class="btn btn-success" role="button" data-bs-toggle="modal" data-bs-target="#masuk">
                    <i class="fa fa-fw fa-arrow-right-to-bracket opacity-50"></i>
                    <span class="d-none d-sm-inline-block ms-1">Masuk</span>
                </a>
            @endif
        </div>
    </div>
    <div id="page-header-loader" class="overlay-header bg-primary-lighter">
        <div class="content-header">
            <div class="w-100 text-center">
                <i class="fa fa-fw fa-circle-notch fa-spin text-primary"></i>
            </div>
        </div>
    </div>
</header>
