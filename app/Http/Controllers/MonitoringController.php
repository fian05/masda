<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bus;
use App\Models\Pelajar;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use App\Models\MonitoringBus;

class MonitoringController extends Controller
{
    public function pelajarView($nisn)
    {
        $pelajar = Pelajar::where("nisn", $nisn)->first();
        return view('monitoring.nisn', compact('pelajar'));
    }

    public function mapsView()
    {
        $buses = Bus::all();
        return view('monitoring.maps', compact('buses'));
    }

    public function mapsUpdate() {
        $markers = [];
        $today = date("Y-m-d", strtotime("2023-07-21"));
        $latestBusData = MonitoringBus::where('created_at', '>', $today)->latest()->get();
        foreach ($latestBusData as $busData) {
            $bus = Bus::where('plat_nomor', $busData->plat_nomor)->first();
            $jmlPenumpang = $bus->jumlah_kursi - $busData->sisa_pnp;
            $markers[] = [
                'plat_nomor' => $busData->plat_nomor,
                'latitude' => $busData->latitude,
                'longitude' => $busData->longitude,
                'sisa_pnp' => $busData->sisa_pnp,
                'jml_pnp' => $jmlPenumpang,
                'date' => $busData->updated_at,
                'rute_awal' => $bus->rute_awal,
                'rute_akhir' => $bus->rute_akhir,
                'jumlah_kursi' => $bus->jumlah_kursi,
            ];
        }
        return response()->json([
            'status' => 'success',
            'data' => $markers,
        ]);
    }

    public function pelajarCek(Request $request) {
        $result = Monitoring::where('nisn', $request->nisn)->exists();
        if ($result) {
            $today = date("Y-m-d", strtotime("2023-07-21"));
            $monitoring = Monitoring::where("nisn", $request->nisn)->where('created_at', '>', $today)->latest()->first();
            $pelajar = Pelajar::findOrFail($request->nisn);
            if($monitoring->status == "in") {
                $posisi = "Sedang menaiki bus";
                $getBus = Bus::where('plat_nomor', $monitoring->plat_nomor)->first();
                if($getBus) {
                    $getMonitoringBus = MonitoringBus::where('plat_nomor', $monitoring->plat_nomor)->where('created_at', '>', $today)->latest()->first();
                    if($getMonitoringBus) {
                        $lat = $getMonitoringBus->latitude;
                        $lng = $getMonitoringBus->longitude;
                        $jmlPenumpang = $getBus->jumlah_kursi - $getMonitoringBus->sisa_pnp;
                        return response()->json([
                            'status' => 'success',
                            'nama' => $pelajar->nama,
                            'posisi' => $posisi,
                            'plat_nomor' => $monitoring->plat_nomor,
                            'latitude_bus' => $lat,
                            'longitude_bus' => $lng,
                            'latitude_tap' => $monitoring->latitude,
                            'longitude_tap' => $monitoring->longitude,
                            'date' => $monitoring->created_at,
                            'date_bus' => $getMonitoringBus->updated_at,
                            'jml_pnp' => $jmlPenumpang,
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'posisi' => 'Belum naik bus hari ini',
                        ]);
                    }
                }
            } else if($monitoring->status == "out") {
                $posisi = "Sudah turun dari bus";
                $getBus = Bus::where('plat_nomor', $monitoring->plat_nomor)->first();
                if($getBus) {
                    return response()->json([
                        'status' => 'success',
                        'nama' => $pelajar->nama,
                        'posisi' => $posisi,
                        'plat_nomor' => $monitoring->plat_nomor,
                        'latitude_bus' => "",
                        'longitude_bus' => "",
                        'latitude_tap' => $monitoring->latitude,
                        'longitude_tap' => $monitoring->longitude,
                        'date' => $monitoring->created_at,
                        'date_bus' => "",
                    ]);
                }
            }
        } else {
            return response()->json([
                'status' => 'error',
                'posisi' => 'Belum naik bus hari ini',
            ]);
        }
    }

    public function tap(Request $request) {
        $iKode_uid = $request->kode_uid;
        $iPlat_nomor = $request->plat_nomor;
        $iLatitude = $request->lat;
        $iLongitude = $request->lng;

        $bus = Bus::where('plat_nomor', $iPlat_nomor)->first();
        if(!$bus) {
            return response()->json([
                'success' => false,
                'message' => "Bus Tidak Terdaftar.",
                'status' => "Gagal",
            ], 404);
        } else {
            $uid = Pelajar::where('uid', $iKode_uid)->first();
            if(!$uid) {
                return response()->json([
                    'success' => false,
                    'message' => "Pelajar Tidak Terdaftar.",
                    'status' => "Gagal"
                ], 404);
            } else {
                if($iLatitude == "0.000000" && $iLongitude == "0.000000") { // Latlong 0
                    return response()->json([
                        'success' => false,
                        'message' => "Lokasi Tidak Valid!",
                        'status' => "Gagal",
                    ], 404);
                } else { // Latlong tidak 0
                    $today = date("Y-m-d", strtotime("2023-07-21"));
                    $cekStatus = Monitoring::where('nisn', $uid->nisn)->where('created_at', '>', $today)->latest()->first();
                    if(!$cekStatus) {
                        $status = "in";
                    } else {
                        if($cekStatus->status == "in") {
                            $status = "out";
                        } else if($cekStatus->status == "out") {
                            $status = "in";
                        }
                    }

                    $monitorings = Monitoring::create([
                        "nisn" => $uid->nisn,
                        "plat_nomor" => $iPlat_nomor,
                        "status" => $status,
                        "latitude" => $iLatitude,
                        "longitude" => $iLongitude,
                        "created_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                        "updated_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                    ]);

                    if(!$monitorings) {
                        return response()->json([
                            'success' => false,
                            'message' => "Data Gagal Diinput.",
                            'status' => "Gagal"
                        ], 404);
                    } else {
                        // Fokus ke Monitoring Bus
                        $pelajar = Monitoring::where('plat_nomor', $iPlat_nomor)->where('created_at', '>', $today)->latest()->get();
                        if($pelajar->count() == 0) { // Belum ada pelajar yang tap kartu hari ini
                            $sisa_pnp = $bus->jumlah_kursi; // Kursi full
                            $monitoringBus = MonitoringBus::where('plat_nomor', $iPlat_nomor)->where('created_at', '>', $today)->first();
                            if(!$monitoringBus) { // Belum ada monitoring bus
                                $monitoringBus = MonitoringBus::create([
                                    "plat_nomor" => $iPlat_nomor,
                                    "latitude" => $iLatitude,
                                    "longitude" => $iLongitude,
                                    "sisa_pnp" => $sisa_pnp,
                                    "created_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                                    "updated_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                                ]);
                            } else { // Sudah ada monitoring bus
                                $monitoringBus->update([
                                    "latitude" => $iLatitude,
                                    "longitude" => $iLongitude,
                                    "sisa_pnp" => $sisa_pnp,
                                    "created_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                                    "updated_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                                ]);
                            }
                        } else { // Sudah ada pelajar yang tap kartu hari ini
                            $in = Monitoring::where('plat_nomor', $iPlat_nomor)
                                ->where('status', 'in')
                                ->where('created_at', '>', $today)
                                ->count();
                            $out = Monitoring::where('plat_nomor', $iPlat_nomor)
                                ->where('status', 'out')
                                ->where('created_at', '>', $today)
                                ->count();
                            $sisa_pnp = $bus->jumlah_kursi - ($in - $out); // Kursi kurang/tetap/tambah, ada yang tap in/out kartu
                            $monitoringBus = MonitoringBus::where('plat_nomor', $iPlat_nomor)->where('created_at', '>', $today)->first();
                            if(!$monitoringBus) { // Belum ada data sama sekali di Monitoring Bus
                                $monitoringBus = MonitoringBus::create([
                                    "plat_nomor" => $iPlat_nomor,
                                    "latitude" => $iLatitude,
                                    "longitude" => $iLongitude,
                                    "sisa_pnp" => $sisa_pnp,
                                    "created_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                                    "updated_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                                ]);
                            } else { // Sudah ada monitoring bus
                                $monitoringBus->update([
                                    "latitude" => $iLatitude,
                                    "longitude" => $iLongitude,
                                    "sisa_pnp" => $sisa_pnp,
                                    "created_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                                    "updated_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                                ]);
                            }
                        }
                        return response()->json([
                            'success' => true,
                            'message' => $uid->nama,
                            'status' => $status,
                        ], 200);
                    }
                }
            }
        }
    }

    public function monitor(Request $request) {
        $iPlat_nomor = $request->plat_nomor;
        $iLatitude = $request->lat;
        $iLongitude = $request->lng;

        $bus = Bus::where('plat_nomor', $iPlat_nomor)->first();
        if(!$bus) { // Plat Nomor tidak terdaftar di sistem
            return response()->json([
                'success' => false,
                'message' => "Plat Nomor Tidak Terdaftar!",
                'status' => "Gagal",
            ], 404);
        } else { // Plat Nomor terdaftar di sistem
            if($iLatitude == "0.000000" && $iLongitude == "0.000000") { // Latlong 0
                return response()->json([
                    'success' => false,
                    'message' => "Lokasi Tidak Valid!",
                    'status' => "Gagal",
                ], 404);
            } else { // Latlong tidak 0
                $today = date("Y-m-d", strtotime("2023-07-21"));
                $pelajar = Monitoring::where('plat_nomor', $iPlat_nomor)->where('created_at', '>', $today)->latest()->get();
                if($pelajar->count() == 0) { // Belum ada pelajar yang tap kartu hari ini
                    $sisa_pnp = $bus->jumlah_kursi; // Kursi full
                    $monitoringBus = MonitoringBus::where('plat_nomor', $iPlat_nomor)->where('created_at', '>', $today)->first();
                    if(!$monitoringBus) { // Belum ada monitoring bus
                        $monitoringBus = MonitoringBus::create([
                            "plat_nomor" => $iPlat_nomor,
                            "latitude" => $iLatitude,
                            "longitude" => $iLongitude,
                            "sisa_pnp" => $sisa_pnp,
                            "created_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                            "updated_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                        ]);
                    } else { // Sudah ada monitoring bus
                        $monitoringBus->update([
                            "latitude" => $iLatitude,
                            "longitude" => $iLongitude,
                            "sisa_pnp" => $sisa_pnp,
                            "created_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                            "updated_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                        ]);
                    }
                } else { // Sudah ada pelajar yang tap kartu hari ini
                    $in = Monitoring::where('plat_nomor', $iPlat_nomor)
                        ->where('status', 'in')
                        ->where('created_at', '>', $today)
                        ->count();
                    $out = Monitoring::where('plat_nomor', $iPlat_nomor)
                        ->where('status', 'out')
                        ->where('created_at', '>', $today)
                        ->count();
                    $sisa_pnp = $bus->jumlah_kursi - ($in - $out); // Kursi kurang/tetap/tambah, ada yang tap in/out kartu
                    $monitoringBus = MonitoringBus::where('plat_nomor', $iPlat_nomor)->where('created_at', '>', $today)->first();
                    if(!$monitoringBus) { // Belum ada data sama sekali di Monitoring Bus
                        $monitoringBus = MonitoringBus::create([
                            "plat_nomor" => $iPlat_nomor,
                            "latitude" => $iLatitude,
                            "longitude" => $iLongitude,
                            "sisa_pnp" => $sisa_pnp,
                            "created_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                            "updated_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                        ]);
                    } else {
                        $monitoringBus->update([
                            "latitude" => $iLatitude,
                            "longitude" => $iLongitude,
                            "sisa_pnp" => $sisa_pnp,
                            "created_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                            "updated_at" => date("Y-m-d H:i:s", strtotime("2023-07-21 10:50:26")),
                        ]);
                    }
                }
                return response()->json([
                    'success' => true,
                    'message' => "Monitoring Bus Update!",
                    'status' => "OK",
                ], 200);
            }
        }
    }

    public function getNotif(Request $request) {
        // Mengambil notifikasi terbaru dari tabel Monitoring
        $notification = Monitoring::where("nisn", $request->nisn)->where('created_at', '>', date("Y-m-d", strtotime("-8 seconds", strtotime(date("Y-m-d", strtotime("2023-07-21"))))))->latest()->first();
        // Memeriksa apakah ada notifikasi
        if ($notification) {
            return response()->json([
                "success" => true,
                "plat_nomor" => $notification->plat_nomor,
                "status" => $notification->status,
                "latitude" => $notification->latitude,
                "longitude" => $notification->longitude,
                "created_at" => $notification->created_at,
            ]);
        } else {
            $pelajar = Pelajar::findOrFail($request->nisn);
            return response()->json([ // Mengembalikan respons JSON jika tidak ada notifikasi
                "success" => false,
                "nama" => $pelajar->nama,
            ]);
        }
    }

    public function listNotif(Request $request) {
        $startDate = date("Y-m-d", strtotime("-7 days", strtotime(date("Y-m-d", strtotime("2023-07-21")))));
        $endDate = date("Y-m-d", strtotime("2023-07-21"));
        // Mengambil notifikasi 30 terbaru dan 7 hari terakhir dari tabel Monitoring
        $notifications = Monitoring::where('nisn', $request->nisn)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->latest()->get();
        // Memeriksa apakah ada notifikasi
        if ($notifications) {
            $data = [];
            foreach ($notifications as $notification) {
                $data[] = [
                    "plat_nomor" => $notification->plat_nomor,
                    "status" => $notification->status,
                    "latitude" => $notification->latitude,
                    "longitude" => $notification->longitude,
                    "created_at" => $notification->created_at,
                ];
            }
            return response()->json([
                "success" => true,
                "data" => $data,
            ]);
        } else {
            return response()->json([ // Mengembalikan respons JSON jika tidak ada notifikasi
                "success" => false,
            ]);
        }
    }
}
