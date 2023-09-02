@extends('layout.app')

@section('title')
    Dashboard
@endsection

@section('head')
    {{-- Ini CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    {{-- Ini JS --}}
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="{{ asset("js/lib/jquery.min.js") }}"></script>
    {{-- Leaflet Routing Machine --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    {{-- Leaflet Moving Marker --}}
    <script src="{{ asset("js/plugins/leaflet/MovingMarker.js") }}"></script>
    {{-- Leaflet Keperluan Fullscreen --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet-fullscreen/dist/leaflet.fullscreen.css" />
    <script src="https://unpkg.com/leaflet-fullscreen/dist/Leaflet.fullscreen.min.js"></script>
    <style>
        #map {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            height: 500px;
            width: 100%;
        }
        .leaflet-control-zoom {
            display: none;
        }
        .leaflet-routing-container {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed">
        @include('layout.sidebar')
        @include('layout.header')
        <main id="main-container">
            <div class="content">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center pt-2">
                    <div class="flex-grow-1 mb-1 mb-md-0">
                        <h1 class="h3 fw-bold mb-2">
                        Dashboard
                        </h1>
                        <h2 class="h6 fw-medium fw-medium text-muted mb-0">
                            Hai, <span id="greeting"></span> <a class="fw-semibold">{{ Auth::user()->nama }}</a>.
                        </h2>
                    </div>
                </div>
            </div>
            <hr>
            <div class="content">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <div class="flex-grow-1 mb-1 mb-md-0">
                        <h2 class="h3 fw-bold mb-4">
                        Monitoring Bus
                        </h2>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">
                        <div id="map"></div>
                    </div>
                </div>
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1 mb-1 mb-md-0">
                        <h2 class="h3 fw-bold mb-2">
                        Informasi Bus
                        </h2>
                    </div>
                </div>
                <div class="row mb-4">
                    @foreach ($buses as $bus)
                        <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3 col-xxl-2 mb-4">
                            <div class="block block-rounded d-flex flex-column h-100 mb-0">
                                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                                    <dl class="mb-0">
                                        <dt class="fs-3 fw-bold">{{ preg_replace('/(\d+)/', ' $1', $bus->kode_bus) }}</dt>
                                        <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">{{ preg_replace('/(\d+)/', ' $1 ', $bus->plat_nomor) }}</dd>
                                    </dl>
                                    <div class="item item-rounded-lg bg-body-light">
                                        <i class="fa fa-bus fs-3 text-primary"></i>
                                    </div>
                                </div>
                                <div class="bg-body-light rounded-bottom">
                                    <a role="button" id="btnDetail" class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" data-bs-toggle="modal" data-bs-target="#modal" data-bs-id="{{ $bus->plat_nomor }}">
                                        <span>Detail Monitoring</span>
                                        <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div> {{-- Page Content --}}
        </main> {{-- Main Container --}}
        @include('layout.footer')
    </div> {{-- Page Container --}}
@endsection

@section('modal')
    <div class="modal fade" id="modal" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-lg"></div></div>
@endsection

@section('script')
    <script>
        // Maps
        var map = L.map('map', {scrollWheelZoom: false}).setView([-6.9019617,110.9404542], 7); // Jawa Timur
        L.control.fullscreen().addTo(map);
        
        map.on('fullscreenchange', function () {
            if (map.isFullscreen()) {
                map.scrollWheelZoom.enable();
            } else {
                map.scrollWheelZoom.disable();
            }
        });

        // Render Maps
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Icon
        var bus = L.icon({
            iconUrl: '{{ asset("media/photos/icon-bus4.png") }}',
            iconSize: [26, 26],
            iconAnchor: [13, 26],
            popupAnchor: [0, -30],
        });

        var markers = [];

        function updateAllMarkers() {
            $.ajax({
                url: '{{ route("monitoring.maps.update") }}',
                method: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.status == 'success') {
                        // Hapus marker yang sudah tidak ada dalam data terbaru
                        Object.keys(markers).forEach(function(platNomor) {
                            if (!response.data.some(function(markerData) {
                                return markerData.plat_nomor === platNomor;
                            })) {
                                markers[platNomor].remove();
                                delete markers[platNomor];
                            }
                        });
                        // Tambahkan marker baru atau update posisi marker yang sudah ada
                        response.data.forEach(function (markerData) {
                            var platNomor = markerData.plat_nomor;
                            lat = markerData.latitude;
                            lng = markerData.longitude;
                            if (markers.hasOwnProperty(platNomor)) {
                                // Update posisi marker yang sudah ada
                                markers[platNomor].moveTo([lat, lng], 1000);
                                markers[platNomor].bindPopup("<b>" + platNomor.replace(/(\d+)/g, ' $1 ') + "</b><br>\
                                    Rute: "+markerData.rute_awal+" - "+markerData.rute_akhir+"<br>\
                                    Jumlah Penumpang: " + markerData.jml_pnp + " dari " + markerData.jumlah_kursi + "<br>\
                                    Sisa: " + markerData.sisa_pnp + " kursi<br>\
                                    Last Update: " + formatDate(markerData.date) + "<br>\
                                    <b><a href='https://maps.google.com/maps?q=" + lat + "," + lng + "' style='text-decoration: none;' target='_blank'>Lihat lokasi di Google Maps</a></b>");
                            } else {
                                // Tambahkan marker baru
                                var marker = L.Marker.movingMarker([[lat, lng]], [], { icon: bus }).addTo(map);
                                marker.bindPopup("<b>" + platNomor.replace(/(\d+)/g, ' $1 ') + "</b><br>\
                                    Rute: "+markerData.rute_awal+" - "+markerData.rute_akhir+"<br>\
                                    Jumlah Penumpang: " + markerData.jml_pnp + " dari " + markerData.jumlah_kursi + "<br>\
                                    Sisa: " + markerData.sisa_pnp + " kursi<br>\
                                    Last Update: " + formatDate(markerData.date) + "<br>\
                                    <b><a href='https://maps.google.com/maps?q=" + lat + "," + lng + "' style='text-decoration: none;' target='_blank'>Lihat lokasi di Google Maps</a></b>");
                                markers[platNomor] = marker;
                            }
                        });
                    }
                }
            });
        }

        function formatDate(date) {
            var options = {
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "numeric",
                second: "numeric",
                timeZoneName: "short"
            };
            var timestamp = new Date(date);
            var dateWIB = timestamp.toLocaleString("id-ID", options);
            if (timestamp.getHours() < 10) {
                dateWIB = dateWIB.replace(/\b(\d{1})\b/g, '0$1');
            }
            return dateWIB;
        }

        // Call the updateAllMarkers function every 7.5 seconds
        setInterval(function() {
            updateAllMarkers();
        }, 8000);

        $('#modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            $.ajax({
                url: '{{ route("detailMonitoring.modal") }}',
                type: 'POST',
                data: {
                    platNomor: button.data('bs-id'),
                    _token: '{{ csrf_token() }}',
                },
                dataType: 'JSON',
                success: function(response) {
                    $('#modal').find('.modal-dialog').html(response.modal);
                }, // {{-- Ajax Success --}}
            }); // {{-- Ajax Function --}}
        }); // {{-- Modal Action --}}
    </script>
@endsection
