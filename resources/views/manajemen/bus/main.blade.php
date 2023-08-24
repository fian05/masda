@extends('layout.app')

@section('title')
    Manajemen Data Bus
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
                                Manajemen Data Bus
                            </h1>
                            <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                                Halaman untuk manajemen data armada bus sekolah.
                            </h2>
                        </div>
                        <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}" class="link-fx">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    Manajemen Data Bus
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
                            List Data Bus
                        </h3>
                        <div class="block-options">
                            <a role="button" id="btnTambahData" class="btn text-primary btn-block-option" data-bs-toggle="modal" data-bs-target="#modal"><i class="fa fa-plus"></i> Tambah Data</a>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-striped table-vcenter">
                                <thead>
                                    <tr>
                                        <th>Plat Nomor</th>
                                        <th>Rute</th>
                                        <th>Kapasitas Pnp.</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($buses as $bus)
                                        <tr>
                                            <td>{{ preg_replace('/(\d+)/', ' $1 ', $bus->plat_nomor) }}</td>
                                            <td>{{ $bus->rute_awal }} - {{ $bus->rute_akhir }}</td>
                                            <td>{{ $bus->jumlah_kursi }} kursi</td>
                                            <td>
                                                <div class="dropdown dropstart">
                                                    <label role="button" class="text-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Pilih Opsi"><i class="fa fa-gear"></i> Opsi</label>
                                                    <div class="dropdown-menu fs-sm" aria-labelledby="btnAksi">
                                                        <form id="delete-form-{{ $bus->plat_nomor }}" action="{{ route('bus.hapus', $bus->plat_nomor) }}" method="POST">
                                                            <a role="button" class="dropdown-item text-warning btnEditData" data-bs-toggle="modal" data-bs-target="#modal" data-bs-id="{{ $bus->plat_nomor }}" title="Edit Data"><i class="fa fa-pencil"></i> Edit Data</a>
                                                            <div class="dropdown-divider"></div>
                                                            @csrf
                                                            @method('DELETE')
                                                            <a role="button" class="dropdown-item text-danger delete-link" id="delete-link-{{ $bus->plat_nomor }}" title="Hapus Data"><i class="fa fa-trash"></i> Hapus Data</a>
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
    <script src="{{ asset('js/pages/das_bus.js') }}"></script>
    <script>
        $('#modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            $.ajax({
                url: '{{ route("bus.modal") }}',
                type: 'POST',
                data: {
                    platNomor: button.data('bs-id'),
                    _token: '{{ csrf_token() }}',
                },
                dataType: 'JSON',
                success: function(response) {
                    $('#modal').find('.modal-dialog').html(response.modal);
                    if(response.platNomor) {
                        $('input[name="bus_jumlahPnp"]').tooltip('dispose');
                        $('input[name="bus_ruteAwal"]').tooltip('dispose');
                        $('input[name="bus_ruteAkhir"]').tooltip('dispose');
                        $('#ig-platNomor').tooltip('dispose');
                    } else {
                        $('input[name="bus_jumlahPnp"]').tooltip({title: 'Masukkan Jumlah Kursi'});
                        $('input[name="bus_ruteAwal"]').tooltip({title: 'Masukkan Lokasi Awal'});
                        $('input[name="bus_ruteAkhir"]').tooltip({title: 'Masukkan Lokasi Akhir'});
                        $('#ig-platNomor').tooltip({title: 'Masukkan Plat Nomor Bus'});
                    }
                    $('#notAvailable').hide(); $('#available').hide();
                    $('input[name="bus_jumlahPnp"]').keyup(function() {
                        var data = $(this).val();
                        if (data.length === 0) {
                            $('input[name="bus_jumlahPnp"]').tooltip('dispose');
                            $('input[name="bus_jumlahPnp"]').tooltip({title: 'Masukkan Jumlah Kursi'});
                        } else {
                            $('input[name="bus_jumlahPnp"]').tooltip('dispose');
                        }
                    });
                    $('input[name="bus_ruteAwal"]').keyup(function() {
                        var data = $(this).val();
                        if (data.length === 0) {
                            $('input[name="bus_ruteAwal"]').tooltip('dispose');
                            $('input[name="bus_ruteAwal"]').tooltip({title: 'Masukkan Lokasi Awal'});
                        } else {
                            $('input[name="bus_ruteAwal"]').tooltip('dispose');
                        }
                    });
                    $('input[name="bus_ruteAkhir"]').keyup(function() {
                        var data = $(this).val();
                        if (data.length === 0) {
                            $('input[name="bus_ruteAkhir"]').tooltip('dispose');
                            $('input[name="bus_ruteAkhir"]').tooltip({title: 'Masukkan Lokasi Akhir'});
                        } else {
                            $('input[name="bus_ruteAkhir"]').tooltip('dispose');
                        }
                    });
                    $('input[name="bus_platNomor"]').on('input', function() {
                        var inputValue = $(this).val().toUpperCase().replace(/\s+/g, ''); // {{-- Konversi input menjadi uppercase dan hapus spasi --}}
                        $(this).val(inputValue);
                    }).keyup(function() {
                        var data = $(this).val();
                        if (data.length === 0) {
                            $('#ig-platNomor').tooltip('dispose');
                            $('#ig-platNomor').tooltip({title: 'Masukkan Plat Nomor Bus'});
                            $('#notAvailable').hide(); $('#available').hide();
                        } else {
                            $.ajax({
                                url: "{{ route('bus.platNomor') }}",
                                method: 'POST',
                                data: {
                                    platNomor: data,
                                    _token: "{{ csrf_token() }}",
                                },
                                dataType: 'JSON',
                                success: function(response) {
                                    if (response.status == 'success') {
                                        $('#ig-platNomor').tooltip('dispose');
                                        $('#ig-platNomor').tooltip({title: response.message});
                                        $('#notAvailable').show();
                                        $('#available').hide();
                                    } else if (response.status == 'error') {
                                        $('#ig-platNomor').tooltip('dispose');
                                        $('#ig-platNomor').tooltip({title: response.message});
                                        $('#available').show();
                                        $('#notAvailable').hide();
                                    }
                                }
                            });
                        }
                    });
                    $('#btn-submit').on('click', function(e) {
                        e.preventDefault();
                        if ($('#form').find('input[name="_method"][value="PUT"]').length) { // {{-- Tombol Edit --}}
                            if ($('#form').find('input[name="bus_ruteAwal"]').val() != "" && $('#form').find('input[name="bus_ruteAkhir"]').val() != "" && $('#form').find('input[type="number"]').val() != "") {
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
                                $('#form').find('input[type="text"], input[type="number"]').each(function() {
                                    if ($(this).val() == "") {
                                        $(this).css('border-color', '#ff0000');
                                        $(this).on('focus', function() {
                                            $(this).css('border-color', '#ccc');
                                        });
                                    }
                                });
                            }
                        } else { // {{-- Tombol Add --}}
                            if($('.i-notavailable').is('.i-notavailable:visible')) {
                                Swal.fire('Mohon cek kembali inputan Anda!', '', 'warning');
                                $('#form').find('input[type="text"], input[type="number"]').each(function() {
                                    if ($(this).val() == "") {
                                        $(this).css('border-color', '#ff0000');
                                        $(this).on('focus', function() {
                                            $(this).css('border-color', '#ccc');
                                        });
                                    }
                                });
                            } else if($('.i-notavailable').is('.i-notavailable:hidden')) {
                                if ($('#form').find('input[name="bus_platNomor"]').val() != "" && $('#form').find('input[name="bus_ruteAwal"]').val() != "" && $('#form').find('input[name="bus_ruteAkhir"]').val() != "" && $('#form').find('input[type="number"]').val() != "") {
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
                                    $('#form').find('input[type="text"], input[type="number"]').each(function() {
                                        if ($(this).val() == "") {
                                            $(this).css('border-color', '#ff0000');
                                            $(this).on('focus', function() {
                                                $(this).css('border-color', '#ccc');
                                            });
                                        }
                                    });
                                }
                            } else { Swal.fire('Oops! Ada Yang Error!', '', 'warning'); }
                        }
                    }); // {{-- Submit Button --}}
                }, // {{-- Ajax Success --}}
            }); // {{-- Ajax Function --}}
        }); // {{-- Modal Action --}}
    </script>
@endsection
