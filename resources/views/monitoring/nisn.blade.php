<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset("media/favicons/favicon.png") }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset("media/favicons/favicon-192x192.png") }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset("media/favicons/apple-touch-icon-180x180.png") }}">
    <title>Monitoring Pelajar {{ $pelajar->nama }} - MASDA</title>
    <!--Ini Css-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css">
    <!--Ini JS-->
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
        #info {
            background-color: #fff;
            box-shadow: 0px 0px 25px #777;
            padding: 5px 25px;
            position: absolute;
            z-index: 1000;
            bottom: 0;
            margin: 0;
            left: 0;
            right: 0;
            border-radius: 20px 20px 0 0;
        }
        .slideDown { display: none; }
        .grabPromo { cursor:default; }
    </style>
</head>
<body>
    <div id="map"></div>
    <div id="info" class="grabPromo">
        <label id="label" style="display: block; text-align: center; font-weight:bold; color: #293989; margin: -5px -25px; padding: 15px 0;">&#9650; Informasi detail</label>
        <div class="slideDown">
            <table width="100%">
                <tr>
                    <td style="vertical-align: top; font-weight: bold; width: 1%;">Pelajar</td>
                    <td style="vertical-align: top; width: 1%;">:</td>
                    <td style="vertical-align: top; width: 98%;"><span id="ket-nama"></span></td>
                </tr>
                <tr>
                    <td style="vertical-align: top; font-weight: bold; width: 1%;">Status</td>
                    <td style="vertical-align: top; width: 1%;">:</td>
                    <td style="vertical-align: top; width: 98%;"><span id="ket-status">-</span></td>
                </tr>
                <tr>
                    <td style="vertical-align: top; font-weight: bold; width: 1%;">Lokasi</td>
                    <td style="vertical-align: top; width: 1%;">:</td>
                    <td style="vertical-align: top; width: 98%;"><span id="ket-lokasi">-</span></td>
                </tr>
                <tr id="tr-jumlah-penumpang" style="display: none;">
                    <td style="vertical-align: top; font-weight: bold; width: 1%;">Penumpang</td>
                    <td style="vertical-align: top; width: 1%;">:</td>
                    <td style="vertical-align: top; width: 98%;"><span id="ket-jumlah-penumpang">-</span></td>
                </tr>
            </table>
        </div>
	</div>
    <script>
        $('#label').click(function(e){
            $('.slideDown').slideToggle();
            var currentText = $(this).text().toLowerCase();
            var newText = currentText.includes('sembunyikan') ? '&#9650; Tampilkan detail' : '&#9660; Sembunyikan detail';
            $(this).html(newText);
        });

        // Maps
        var map = L.map('map').setView([-7.2816604,112.716688], 11); // Kota Surabaya

        // Render Maps
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Icon
        var defaultIcon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],   // Posisi ancor ikon di bagian bawah tengah
            popupAnchor: [0, -41]   // Posisi ancor pop-up di bagian atas tengah
        });
        var bus = L.icon({
            iconUrl: '{{ asset("media/photos/icon-bus4.png") }}',
            iconSize: [26, 26],
            iconAnchor: [13, 26],
            popupAnchor: [0, -30],
        });

        var markers = [];
        var lat = "";
        var lng = "";
        var marker = L.Marker.movingMarker([[lat, lng]], [], {icon: bus}).addTo(map);
        document.getElementById('ket-nama').innerHTML = "{{ $pelajar->nama }}";

        function updateMarkerPelajar() {
            $.ajax({
                url: '{{ route("monitoring.pelajar.check") }}',
                method: 'post',
                data: {
                    nisn: '{{ $pelajar->nisn }}',
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.status == 'success') {
                        // Format tanggal dalam bahasa Indonesia
                        var options = {
                            year: "numeric",
                            month: "long",
                            day: "numeric",
                            hour: "2-digit",
                            minute: "numeric",
                            second: "numeric",
                            timeZoneName: "short"
                        };
                        if (response.posisi.includes("turun")) { // TURUN
                            lat = response.latitude_tap;
                            lng = response.longitude_tap;
                            var popupContent = "<b>"+response.nama+"</b><br>sudah turun di sini.<br>\
                            <a href='https://maps.google.com/maps?q=" + lat + "," + lng + "' style='text-decoration: none;'>Lihat lokasi di Google Maps</a>";
                            marker.setIcon(defaultIcon);
                            marker.setZIndexOffset(0);
                            document.getElementById('tr-jumlah-penumpang').style.display = 'none';
                        } else { // MENAIKI
                            lat = response.latitude_bus;
                            lng = response.longitude_bus;
                            var timestamp2 = new Date(response.date_bus);
                            var dateWIB2 = timestamp2.toLocaleString("id-ID", options);
                            if (timestamp2.getHours() < 10) {
                                dateWIB2 = dateWIB2.replace(/\b(\d{1})\b/g, '0$1');
                            }
                            var lastUpdate = "<br>Last Update: "+dateWIB2+"<br>";
                            var popupContent = "Posisi <b>"+response.nama+"</b> saat ini."+lastUpdate+"<a href='https://maps.google.com/maps?q=" + lat + "," + lng + "' style='text-decoration: none;'>Lihat lokasi di Google Maps</a>";
                            marker.setIcon(bus);
                            marker.setZIndexOffset(1000);
                            document.getElementById('tr-jumlah-penumpang').style.display = 'table-row';
                            document.getElementById('ket-jumlah-penumpang').innerHTML = "Ada " + response.jml_pnp + " penumpang";
                        }
                        // Update Lat Long
                        marker.moveTo([lat, lng], 1000);
                        // Update popup content
                        marker.bindPopup(popupContent);
                        // Center the map on the marker
                        map.panTo([lat, lng]);
                        // Konversi ke objek Date
                        var timestamp = new Date(response.date);
                        var dateWIB = timestamp.toLocaleString("id-ID", options);
                        if (timestamp.getHours() < 10) {
                            dateWIB = dateWIB.replace(/\b(\d{1})\b/g, '0$1');
                        }

                        document.getElementById('ket-nama').innerHTML = response.nama;
                        document.getElementById('ket-status').innerHTML = response.posisi+" sekolah <b>"+response.plat_nomor.replace(/(\d+)/g, ' $1 ')+"</b> pada "+dateWIB;
                        var link = document.createElement('a');
                        link.setAttribute('href', 'https://maps.google.com/maps?q='+response.latitude_tap+','+response.longitude_tap);
                        link.setAttribute('target', '_blank');
                        link.innerHTML = 'Lihat lokasi di Google Maps';
                        link.style.textDecoration = 'none';
                        document.getElementById('ket-lokasi').innerHTML = '';
                        document.getElementById('ket-lokasi').appendChild(link);
                    } else if (response.status == 'error') {
                        map.removeLayer(marker);
                        document.getElementById('ket-status').innerHTML = response.posisi;
                    }
                }
            });
        }

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

        // Call the updateMarker function every 8 seconds
        setInterval(function() {
            updateMarkerPelajar();
            updateAllMarkers();
        }, 8000);
    </script>
</body>
</html>