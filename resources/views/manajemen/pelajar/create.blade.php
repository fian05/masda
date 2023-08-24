@extends('layout.app')

@section('title')
    Input Data Pelajar {{ $sekolahs->nama_sekolah }}
@endsection

@section('content')
    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed">
        @include('layout.sidebar')
        @include('layout.header')
        <main id="main-container">
            <div class="bg-body-light">
                <div class="content content-full">
                    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                        <div class="flex-grow-1">
                            <h1 class="h3 fw-bold mb-2">
                                Input Data Pelajar {{ $sekolahs->nama_sekolah }}
                            </h1>
                            <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                                Halaman untuk menginput data pelajar <b>{{ $sekolahs->nama_sekolah }}</b> untuk didaftarkan pada program bus sekolah.
                            </h2>
                        </div>
                        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('pelajar') }}" class="link-fx">Manajemen Data Pelajar</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    Input
                                </li>
                            </ol>
                        </nav>
                    </div> {{-- d-flex flex-column --}}
                </div> {{-- content content-full --}}
            </div> {{-- bg-body-light --}}
            <div class="content content-full">
                <p class="mb-2"><small class="text-danger">* Inputan Wajib Diisi</small></p>
                <form id="form" method="POST" action="{{ route('pelajar.store') }}">
                    @csrf
                    <div class="row items-push" id="dynamicRow">
                        <div class="col-xl-6 col-lg-6 baru-data">
                            <div class="block block-rounded h-100 mb-0">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">Form Input Data</h3>
                                    <div class="block-options">
                                        <a role="button" class="btn btn-block-option text-success btn-tambah" title="Tambah Data">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                        <a role="button" class="btn btn-block-option text-danger btn-hapus" style="display: none;" title="Hapus Data">
                                            <i class="fa fa-minus"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="row">
                                        <div class="col-md mb-3">
                                            <div class="input-group" id="ig-nisn0">
                                                <div class="form-floating">
                                                    <input type="text" pattern="\d*" class="form-control form-control-alt form-control-lg pelajar-nisn" name="pelajar[0][nisn]" pattern="[0-9]+" placeholder=" " autocomplete="off" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" autofocus required>
                                                    <label>NISN <small class="text-danger">*</small></label>
                                                </div>
                                                <span class="input-group-text">
                                                    <i class="fa fa-check-circle text-success" id="nisnAvailable0"></i>
                                                    <i class="fa fa-exclamation-circle text-danger i-notavailable" id="nisnNot-available0"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md mb-3">
                                            <div class="input-group" id="ig-uid0">
                                                <div class="form-floating">
                                                    <input type="text" pattern="\d*" class="form-control form-control-alt form-control-lg pelajar-uid" name="pelajar[0][uid]" pattern="[0-9]+" placeholder=" " autocomplete="off" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required>
                                                    <label>UID Tag <small class="text-danger">*</small></label>
                                                </div>
                                                <span class="input-group-text">
                                                    <i class="fa fa-check-circle text-success" id="kode-uidAvailable0"></i>
                                                    <i class="fa fa-exclamation-circle text-danger i-notavailable" id="kode-uidNot-available0"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control form-control-alt form-control-lg" name="pelajar[0][nama]" placeholder=" " autocomplete="off" required>
                                                <label>Nama <small class="text-danger">*</small></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md mb-3">
                                            <div class="form-floating">
                                                <select class="form-select form-control form-control-alt form-control-lg" name="pelajar[0][jk]" required>
                                                    <option value="" hidden readonly selected>- Belum Memilih -</option>
                                                    <option value="l">Laki-laki</option>
                                                    <option value="p">Perempuan</option>
                                                </select>
                                                <label>Jenis Kelamin <small class="text-danger">*</small></label>
                                            </div>
                                        </div>
                                        <div class="col-md mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control form-control-alt form-control-lg" name="pelajar[0][nohp]" pattern="[0-9]+" placeholder=" " autocomplete="off" required>
                                                <label>No. HP Orang Tua <small class="text-danger">*</small></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3">
                                            <div class="form-floating">
                                                <textarea class="form-control form-control-alt form-control-lg" name="pelajar[0][alamat]" placeholder=" " style="height: 100px" required></textarea>
                                                <label>Alamat <small class="text-danger">*</small></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="btn-simpan"><i class="fa fa-save"></i> Submit</button>
                    <a href="{{ route("pelajar") }}" class="btn btn-primary btn-danger" id="btn-cancel"><i class="fa fa-x"></i> Batal</a>
                </form>

            </div> {{-- Page Content --}}
        </main> {{-- Main Container --}}
        @include('layout.footer')
    </div> {{-- Page Container --}}
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            check(0);
        });
        // Cek Duplicate
        function hasDuplicates(array) {
            return (new Set(array)).size !== array.length;
        }
        var no = 0;
        function check(no) {
            $('input[name="pelajar['+no+'][uid]"], input[name="pelajar['+no+'][nisn]"]').on('input', function() {
                // Konversi input menjadi uppercase dan hapus spasi
                var inputValue = $(this).val().toUpperCase().replace(/\s+/g, '');
                // Hanya izinkan input berupa angka
                if (!isNaN(inputValue)) {
                    $(this).val(inputValue);
                } else {
                    $(this).val('');
                }
            });
            $('#ig-uid'+no).tooltip('dispose');
            $('#ig-uid'+no).tooltip({title: 'Masukkan kode UID tag'});
            $('#kode-uidNot-available'+no).hide(); $('#kode-uidAvailable'+no).hide();
            $('#ig-nisn'+no).tooltip('dispose');
            $('#ig-nisn'+no).tooltip({title: 'Masukkan NISN'});
            $('#nisnNot-available'+no).hide(); $('#nisnAvailable'+no).hide();
            $('input[name="pelajar['+no+'][uid]"]').keyup(function() { // Kode UID
                var data = $(this).val();
                if (data.length === 0) {
                    $('#ig-uid'+no).tooltip('dispose');
                    $('#ig-uid'+no).tooltip({title: 'Masukkan kode UID tag'});
                    $('#kode-uidNot-available'+no).hide(); $('#kode-uidAvailable'+no).hide();
                } else {
                    $.ajax({
                        url: "{{ route('pelajar.uid') }}",
                        method: 'post',
                        data: {
                            uid: data,
                            _token: "{{ csrf_token() }}",
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            var inputValues = $('.pelajar-uid').map(function() { return $(this).val(); }).get(); // get an array of input values
                            if (hasDuplicates(inputValues)) {
                                $('#ig-uid'+no).tooltip('dispose');
                                $('#ig-uid'+no).tooltip({title: 'Kode UID sudah diinput di form lain'});
                                $('#kode-uidNot-available'+no).show(); $('#kode-uidAvailable'+no).hide();
                            } else {
                                if (response.status == 'success') {
                                    $('#ig-uid'+no).tooltip('dispose');
                                    $('#ig-uid'+no).tooltip({title: response.message});
                                    $('#kode-uidNot-available'+no).show(); $('#kode-uidAvailable'+no).hide();
                                } else if (response.status == 'error') {
                                    $('#ig-uid'+no).tooltip('dispose');
                                    $('#ig-uid'+no).tooltip({title: response.message});
                                    $('#kode-uidAvailable'+no).show(); $('#kode-uidNot-available'+no).hide();
                                }
                            }
                        }
                    });
                }
            });
            $('input[name="pelajar['+no+'][nisn]"]').keyup(function() { // NISN
                var data = $(this).val();
                if (data.length === 0) {
                    $('#ig-nisn'+no).tooltip('dispose');
                    $('#ig-nisn'+no).tooltip({title: 'Masukkan NISN'});
                    $('#nisnNot-available'+no).hide(); $('#nisnAvailable'+no).hide();
                } else {
                    $.ajax({
                        url: "{{ route('pelajar.nisn') }}",
                        method: 'POST',
                        data: {
                            nisn: data,
                            _token: "{{ csrf_token() }}",
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            var inputValues = $('.pelajar-nisn').map(function() { return $(this).val(); }).get(); // get an array of input values
                            if (hasDuplicates(inputValues)) {
                                $('#ig-nisn'+no).tooltip('dispose');
                                $('#ig-nisn'+no).tooltip({title: 'NISN sudah diinput di form lain'});
                                $('#nisnNot-available'+no).show(); $('#nisnAvailable'+no).hide();
                            } else {
                                if (response.status == 'success') {
                                    $('#ig-nisn'+no).tooltip('dispose');
                                    $('#ig-nisn'+no).tooltip({title: response.message});
                                    $('#nisnNot-available'+no).show(); $('#nisnAvailable'+no).hide();
                                } else if (response.status == 'error') {
                                    $('#ig-nisn'+no).tooltip('dispose');
                                    $('#ig-nisn'+no).tooltip({title: response.message});
                                    $('#nisnAvailable'+no).show(); $('#nisnNot-available'+no).hide();
                                }
                            }
                        }
                    });
                }
            });
        }
        $("#dynamicRow").on("click", ".btn-tambah", function() {
            no++;
            addForm(no);
            check(no);
            $(this).css("display", "none");
            var valtes = $(this).parent().find(".btn-hapus").css("display", "");
        })
        $("#dynamicRow").on("click", ".btn-hapus", function() {
            $(this).parent().parent().parent().parent('.baru-data').remove();
            var bykrow = $(".baru-data").length;
            if (bykrow == 1) {
                $(".btn-hapus").css("display", "none")
                $(".btn-tambah").css("display", "");
            } else {
                $('.baru-data').last().find('.btn-tambah').css("display", "");
            }
        });
        $('#btn-simpan').on('click', function(e) {
            e.preventDefault();
            if($('.i-notavailable').is('.i-notavailable:visible')) {
                Swal.fire('Mohon cek kembali inputan Anda!', '', 'error');
                $('#dynamicRow').find('input[type="text"], input[type="number"], select, textarea').each(function() {
                    if ($(this).val() == "") {
                        $(this).css('border-color', '#ff0000');
                        $(this).on('focus', function() {
                            $(this).css('border-color', '#ccc');
                        });
                    }
                });
            } else if($('.i-notavailable').is('.i-notavailable:hidden')) {
                if ($('#dynamicRow').find('input[type="text"]').val() != "" && $('#dynamicRow').find('input[type="number"]').val() != "" && $('#dynamicRow').find('select').val() != "" && $('#dynamicRow').find('textarea').val() != "") {
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Apakah data yang Anda input sudah benar?',
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
                } else {
                    Swal.fire('Lengkapi Inputan Formulir!', '', 'error');
                    $('#dynamicRow').find('input[type="text"], input[type="number"], select, textarea').each(function() {
                        if ($(this).val() == "") {
                            $(this).css('border-color', '#ff0000');
                            $('#dynamicRow').find('input[type="text"].bus-keterangan').each(function() {
                                $(this).css('border-color', '#ccc');
                            });
                            $(this).on('focus', function() {
                                $(this).css('border-color', '#ccc');
                            });
                        }
                    });
                }
            } else {
                Swal.fire('Oops! Ada Yang Error!', '', 'question');
            }
        });
        function addForm(no) {
            var addrow =
                '   <div class="col-xl-6 col-lg-6 baru-data">\
                        <div class="block block-rounded h-100 mb-0">\
                            <div class="block-header block-header-default">\
                                <h3 class="block-title">Form Input Data</h3>\
                                <div class="block-options">\
                                    <a role="button" class="btn btn-block-option text-success btn-tambah" title="Tambah Data">\
                                        <i class="fa fa-plus"></i>\
                                    </a>\
                                    <a role="button" class="btn btn-block-option text-danger btn-hapus" title="Hapus Data">\
                                        <i class="fa fa-minus"></i>\
                                    </a>\
                                </div>\
                            </div>\
                            <div class="block-content">\
                                <div class="row">\
                                    <div class="col-md mb-3">\
                                        <div class="input-group" id="ig-nisn'+no+'">\
                                            <div class="form-floating">\
                                                <input type="text" pattern="\d*" class="form-control form-control-alt form-control-lg pelajar-nisn" name="pelajar['+no+'][nisn]" pattern="[0-9]+" placeholder=" " autocomplete="off" required>\
                                                <label>NISN <small class="text-danger">*</small></label>\
                                            </div>\
                                            <span class="input-group-text">\
                                                <i class="fa fa-check-circle text-success" id="nisnAvailable'+no+'"></i>\
                                                <i class="fa fa-exclamation-circle text-danger i-notavailable" id="nisnNot-available'+no+'"></i>\
                                            </span>\
                                        </div>\
                                    </div>\
                                    <div class="col-md mb-3">\
                                        <div class="input-group" id="ig-uid'+no+'">\
                                            <div class="form-floating">\
                                                <input type="text" pattern="\d*" class="form-control form-control-alt form-control-lg pelajar-uid" name="pelajar['+no+'][uid]" pattern="[0-9]+" placeholder=" " autocomplete="off" autofocus required>\
                                                <label>UID tag <small class="text-danger">*</small></label>\
                                            </div>\
                                            <span class="input-group-text">\
                                                <i class="fa fa-check-circle text-success" id="kode-uidAvailable'+no+'"></i>\
                                                <i class="fa fa-exclamation-circle text-danger i-notavailable" id="kode-uidNot-available'+no+'"></i>\
                                            </span>\
                                        </div>\
                                    </div>\
                                </div>\
                                <div class="row">\
                                    <div class="col-md mb-3">\
                                        <div class="form-floating">\
                                            <input type="text" class="form-control form-control-alt form-control-lg" name="pelajar['+no+'][nama]" placeholder=" " autocomplete="off" required>\
                                            <label>Nama <small class="text-danger">*</small></label>\
                                        </div>\
                                    </div>\
                                </div>\
                                <div class="row">\
                                    <div class="col-md mb-3">\
                                        <div class="form-floating">\
                                            <select class="form-select form-control form-control-alt form-control-lg" name="pelajar['+no+'][jk]" required>\
                                                <option value="" hidden readonly selected>- Belum Memilih -</option>\
                                                <option value="l">Laki-laki</option>\
                                                <option value="p">Perempuan</option>\
                                            </select>\
                                            <label>Jenis Kelamin <small class="text-danger">*</small></label>\
                                        </div>\
                                    </div>\
                                    <div class="col-md mb-3">\
                                        <div class="form-floating">\
                                            <input type="text" class="form-control form-control-alt form-control-lg" name="pelajar['+no+'][nohp]" pattern="[0-9]+" placeholder=" " autocomplete="off" required>\
                                            <label>No. HP Orang Tua <small class="text-danger">*</small></label>\
                                        </div>\
                                    </div>\
                                </div>\
                                <div class="row">\
                                    <div class="mb-3">\
                                        <div class="form-floating">\
                                            <textarea class="form-control form-control-alt form-control-lg" name="pelajar['+no+'][alamat]" placeholder=" " style="height: 100px" required></textarea>\
                                            <label>Alamat <small class="text-danger">*</small></label>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                    </div>'
            $("#dynamicRow").append(addrow);
        }
    </script>
@endsection
