@extends('layout.app')

@section('title')
    Beranda
@endsection

@section('head')
    <style>
        /* for small screens */
    @media (max-width: 576px) {
        .img-fluid {
            width: 90%;
        }
    }

    /* for larger screens */
    @media (min-width: 576px) {
        .img-fluid {
            max-width: 30%;
        }
    }
    </style>
@endsection

@section('content')
    <div id="page-container">
        @include('layout.nav')
        
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="container text-center">
                <img class="mb-4 img-fluid" src="{{ asset("media/photos/icon-bus.png") }}">
                <h1 class="fw-normal mb-3">Selamat Datang Di Website <span class="fw-bold">MAS</span><span class="fw-medium">DA</span></h1>
                <h2 class="fw-medium mb-2">Monitoring Angkutan Sekolah Daerah</h2>
                <p class="fs-lg fw-normal text-muted m-0">
                    <span class="fw-bold">MASDA</span> merupakan sistem monitoring angkutan sekolah untuk meningkatkan keamanan dan kualitas transportasi pelajar berbasis IoT.
                </p>
            </div>
        </div>

        @include('layout.footer')
    {{-- </div> --}}
@endsection

@section('modal')
    <!-- Modal Login -->
    <div class="modal fade" id="masuk" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog"></div>
    </div>
@endsection

@section('script')
    <script>
        // Action untuk Modal
        $('#masuk').on('show.bs.modal', function (event) {
            // Mendapatkan tombol yang memicu modal
            var button = $(event.relatedTarget)
            $.ajax({
                url: '{{ route("login.modal") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                dataType: 'JSON',
                success: function(response) {
                    $('#masuk').find('.modal-dialog').html(response.modal);
                    $('button[type="submit"]').on('click', function(e) {
                        e.preventDefault();
                        if ($('form').find('input[type="email"]').val() != "" && $('form').find('input[type="password"]').val() != "") {
                            $('form').submit();
                        } else {
                            Swal.fire('Lengkapi Isian Yang Wajib Diisi!', '', 'error');
                            $('form').find('input[type="email"], input[type="password"]').each(function() {
                                if ($(this).val() == "") {
                                    $(this).css('border-color', '#ff0000');
                                    $(this).on('focus', function() {
                                        $(this).css('border-color', '#ccc');
                                    });
                                }
                            });
                        }
                    });
                },
            });
        });
    </script>
@endsection
