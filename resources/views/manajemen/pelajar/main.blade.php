@extends('layout.app')

@section('title')
    Manajemen Data Pelajar {{ $sekolahs->nama_sekolah }}
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
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
                                Manajemen Data Pelajar {{ $sekolahs->nama_sekolah }}
                            </h1>
                            <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                                Halaman untuk manajemen data pelajar <b>{{ $sekolahs->nama_sekolah }}</b> yang terdaftar pada program bis sekolah.
                            </h2>
                        </div>
                        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    Manajemen Data Pelajar
                                </li>
                            </ol>
                        </nav>
                    </div> {{-- d-flex flex-column --}}
                </div> {{-- content content-full --}}
            </div> {{-- bg-body-light --}}
            <div class="content">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            List Data Pelajar
                        </h3>
                        <div class="block-options">
                            <a href="{{ route('pelajar.input') }}" role="button" id="btnTambahData" class="btn text-primary btn-block-option"><i class="fa fa-plus"></i> Tambah Data</a>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-striped table-vcenter">
                                <thead>
                                    <tr>
                                        <th>NISN</th>
                                        <th>UID</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>No. HP</th>
                                        <th>Alamat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pelajars as $pelajar)
                                        <tr>
                                            <td>{{ $pelajar->nisn }}</td>
                                            <td>{{ $pelajar->uid }}</td>
                                            <td>{{ $pelajar->nama }}</td>
                                            <td>
                                                @if ($pelajar->jk == 'l')
                                                    Laki-laki
                                                @elseif ($pelajar->jk == 'p')
                                                    Perempuan
                                                @endif
                                            </td>
                                            <td>{{ $pelajar->nohp }}</td>
                                            <td>{{ $pelajar->alamat }}</td>
                                            <td>
                                                <div class="dropdown dropstart">
                                                    <label role="button" class="text-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Pilih Opsi"><i class="fa fa-gear"></i> Opsi</label>
                                                    <div class="dropdown-menu fs-sm" aria-labelledby="btnAksi">
                                                        <a role="button" class="dropdown-item text-warning btnEditData" data-bs-toggle="modal" data-bs-target="#modal" data-bs-id="{{ $pelajar->nisn }}" title="Edit Data"><i class="fa fa-pencil"></i> Ubah Data</a>
                                                        <div class="dropdown-divider"></div>
                                                        <form id="delete-form-{{ $pelajar->nisn }}" action="{{ route('pelajar.hapus', $pelajar->nisn) }}" method="POST" class="d-inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="text" name="namaHps" value="{{ $pelajar->nama }}" hidden>
                                                            <a role="button" class="dropdown-item text-danger delete-link" id="delete-link-{{ $pelajar->nisn }}" title="Hapus Data"><i class="fa fa-trash"></i> Hapus Data</a>
                                                        </form>
                                                        <form id="reset-form-{{ $pelajar->nisn }}" action="{{ route('pelajar.reset', $pelajar->nisn) }}" method="POST" class="d-inline-block">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="text" name="namaRst" value="{{ $pelajar->nama }}" hidden>
                                                            <a role="button" class="dropdown-item text-secondary reset-link" id="reset-link-{{ $pelajar->nisn }}" title="Reset Password"><i class="fa fa-key"></i> Reset Password</a>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>{{-- table-responsive --}}
                    </div> {{-- block-content --}}
                </div> {{-- Block --}}
            </div> {{-- Page Content --}}
        </main> {{-- Main Container --}}
        @include('layout.footer')
    </div> {{-- Page Container --}}
@endsection

@section('modal')
    <div class="modal fade" id="modal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-lg"></div></div>
@endsection

@section('script')
<script src="{{ asset('js/pages/das_pelajar.js') }}"></script>
    <script>
        $('#modal').on('show.bs.modal', function (event) {
            // Mendapatkan tombol yang memicu modal
            var button = $(event.relatedTarget)
            $.ajax({
                url: '{{ route("pelajar.modal") }}',
                type: 'POST',
                data: {
                    nisn: button.data('bs-id'),
                    _token: '{{ csrf_token() }}',
                },
                dataType: 'JSON',
                success: function(response) {
                    $('#modal').find('.modal-dialog').html(response.modal);
                    $('input[name="pelajar_uid"]').tooltip('dispose');
                    $('#uid-notAvailable').hide(); $('#uid-available').hide();
                    $('input[name="pelajar_uid"]').keyup(function() { // Kode UID
                        var data = $(this).val();
                        if (data.length === 0) {
                            $('#ig-uid').tooltip('dispose');
                            $('#ig-uid').tooltip({title: 'Masukkan kode UID tag'});
                            $('#uid-notAvailable').hide(); $('#uid-available').hide();
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
                                    if (response.status == 'success') {
                                        $('#ig-uid').tooltip('dispose');
                                        $('#ig-uid').tooltip({title: response.message});
                                        $('#uid-notAvailable').show(); $('#uid-available').hide();
                                        if (response.uidExists) {
                                            $('#ig-uid').tooltip('dispose');
                                            $('#ig-uid').tooltip({title: "Kode UID Tidak Berubah"});
                                            $('#uid-available').show(); $('#uid-notAvailable').hide();
                                        }
                                    } else if (response.status == 'error') {
                                        $('#ig-uid').tooltip('dispose');
                                        $('#ig-uid').tooltip({title: response.message});
                                        $('#uid-available').show(); $('#uid-notAvailable').hide();
                                    }
                                }
                            });
                        }
                    });

                    $('#btn-submit').on('click', function(e) {
                        e.preventDefault();
                        if($('.i-notavailable').is('.i-notavailable:visible')) { // {{-- Kode Sekolah / Email sudah terdaftar/tidak valid --}}
                        Swal.fire('Mohon cek kembali inputan Anda!', '', 'warning');
                            $('#form').find('input[name="pelajar_uid"]').each(function() {
                                $(this).css('border-color', '#ff0000');
                                $(this).on('focus', function() {
                                    $(this).css('border-color', '#ccc');
                                });
                            });
                        } else if($('.i-notavailable').is('.i-notavailable:hidden')) { // {{-- Inputan sudah benar, divalidasi lagi --}}
                            if ($('#form').find('input[type="text"]').val() != "" && $('#form').find('input[type="number"]').val() != "" && $('#form').find('select').val() != "" && $('#form').find('textarea').val() != "") {
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
                                $('#form').find('input[type="text"], input[type="number"], select, textarea').each(function() {
                                    if ($(this).val() == "") {
                                        $(this).css('border-color', '#ff0000');
                                        $(this).on('focus', function() {
                                            $(this).css('border-color', '#ccc');
                                        });
                                    }
                                });
                            }
                        }
                    });
                },
            });
        });
    </script>
@endsection
