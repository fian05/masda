@extends('layout.app')

@section('title')
    Ubah Password Akun
@endsection

@section('content')
    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed">
        <!-- Sidebar -->
        @include('layout.sidebar')

        <!-- Header -->
        @include('layout.header')

        <!-- Main Container -->
        <main id="main-container">
            <!-- Hero -->
            <div class="bg-body-light">
                <div class="content content-full">
                    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                        <div class="flex-grow-1">
                            <h1 class="h3 fw-bold mb-2">
                                Ubah Password Akun
                            </h1>
                            <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                                Silahkan buat password baru untuk keamanan akun Anda.
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Hero -->

            <!-- Page Content -->
            <div class="content content-full">

                <form id="form" method="POST" action="{{ route('user.updatePassword', Auth::user()->email) }}">
                    @csrf
                    @method('PUT')
                    <div class="row items-push" id="dynamicRow">
                        <div class="col-12 baru-data">
                            <div class="block block-rounded h-100 mb-0">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">Form Buat Password Baru Akun</h3>
                                </div>
                                <div class="block-content">
                                    <div class="row">
                                        <div class="col-md mb-3">
                                            <div class="form-floating">
                                                <input type="password" class="form-control form-control-alt form-control-lg" minlength="8" maxlength="16" id="password_old" name="password_old" placeholder=" " autocomplete="off" required>
                                                <label for="password_old">Password Lama Anda <small class="text-danger">*</small></label>
                                            </div>
                                        </div>
                                        <div class="col-md mb-3">
                                            <div class="form-floating">
                                                <input type="password" class="form-control form-control-alt form-control-lg" minlength="8" maxlength="16" id="password_new" name="password_new" placeholder=" " autocomplete="off" required>
                                                <label for="password_new">Password Baru Anda <small class="text-danger">*</small></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="btn-simpan"><i class="fa fa-save"></i> Submit</button>
                </form>

            </div>
            <!-- END Page Content -->
        </main>
        <!-- END Main Container -->

        <!-- Footer -->
        @include('layout.footer')

    </div>
    <!-- END Page Container -->
@endsection

@section('script')
    <script>
        $('#btn-simpan').on('click', function(e) {
            e.preventDefault();
            if ($('#form').find('input[name="password_old"]').val() == "" || $('#form').find('input[name="password_new"]').val() == "") {
                Swal.fire('Lengkapi Isian Yang Wajib Diisi!', '', 'error');
                $('#form').find('input[type="password"]').each(function() {
                    if ($(this).val() == "") {
                        $(this).css('border-color', '#ff0000');
                        $(this).on('focus', function() {
                            $(this).css('border-color', '#ccc');
                        });
                    }
                });
            } else if($('#form').find('input[name="password_old"]').val().length < 8 || $('#form').find('input[name="password_old"]').val().length > 16) {
                Swal.fire('Password minimal 8 karakter dan maksimal 16 karakter', '', 'error');
                $('#form').find('input[name="password_old"]').each(function() {
                    $(this).css('border-color', '#ff0000');
                    $(this).on('focus', function() {
                        $(this).css('border-color', '#ccc');
                    });
                });
            } else if($('#form').find('input[name="password_new"]').val().length < 8 || $('#form').find('input[name="password_new"]').val().length > 16) {
                Swal.fire('Password minimal 8 karakter dan maksimal 16 karakter', '', 'error');
                $('#form').find('input[name="password_new"]').each(function() {
                    $(this).css('border-color', '#ff0000');
                    $(this).on('focus', function() {
                        $(this).css('border-color', '#ccc');
                    });
                });
            } else {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah password yang Anda input sudah benar?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, benar',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.value) {
                        $('#form').submit();
                    }
                });
            }
        });
    </script>
@endsection
