<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset("media/favicons/favicon.png") }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset("media/favicons/favicon-192x192.png") }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset("media/favicons/apple-touch-icon-180x180.png") }}">
    <title>Monitoring Bus Sekolah - MASDA</title>
    <!-- Ini CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    <!-- Ini JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="{{ asset("js/lib/jquery.min.js") }}"></script>
    <!-- Leaflet Routing Machine -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <!-- Leaflet Moving Marker -->
    <script src="{{ asset("js/plugins/leaflet/MovingMarker.js") }}"></script>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            width: 100%;
        }
        #map {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            height: 100%;
            width: auto;
        }
        .leaflet-control-zoom {
            display: none;
        }
        .leaflet-routing-container {
            display: none;
        }
    </style>
</head>
<body>
    <div id="map"></div>
    <script>
        // Maps
        var map = L.map('map').setView([-6.9019617,110.9404542], 7); // Jawa Timur

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
                                markers[platNomor].bindPopup("<b>"+platNomor.replace(/(\d+)/g, ' $1 ')+"</b><br>\
                                    Rute: "+markerData.rute_awal+" - "+markerData.rute_akhir+"<br>\
                                    Jumlah Penumpang: "+markerData.jml_pnp+" dari " + markerData.jumlah_kursi + "<br>\
                                    Sisa: "+markerData.sisa_pnp+" kursi<br>\
                                    Last Update: "+formatDate(markerData.date)+"<br>\
                                    <b><a href='https://maps.google.com/maps?q="+lat+","+lng+"' style='text-decoration: none;' target='_blank'>Lihat lokasi di Google Maps</a></b>");
                            } else {
                                // Tambahkan marker baru
                                var marker = L.Marker.movingMarker([[lat, lng]], [], { icon: bus }).addTo(map);
                                marker.bindPopup("<b>" + platNomor.replace(/(\d+)/g, ' $1 ') + "</b><br>\
                                    Rute: "+markerData.rute_awal+" - "+markerData.rute_akhir+"<br>\
                                    Jumlah Penumpang: "+markerData.jml_pnp+" dari "+markerData.jumlah_kursi+"<br>\
                                    Sisa: "+markerData.sisa_pnp+" kursi<br>\
                                    Last Update: "+formatDate(markerData.date)+"<br>\
                                    <b><a href='https://maps.google.com/maps?q="+lat+","+lng+"' style='text-decoration: none;' target='_blank'>Lihat lokasi di Google Maps</a></b>");
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
    </script>
</body>
</html>