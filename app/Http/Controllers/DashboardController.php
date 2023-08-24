<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bus;
use App\Models\Admin;
use App\Models\Pelajar;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use App\Models\MonitoringBus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index() {
        $buses = Bus::all();
        return view('dashboard', compact('buses'));
    }

    public function viewUbahPassword() {
        return view('ubahPassword');
    }

    public function updatePassword(Request $request, $email) {
        $this->validate($request, [
            'password_old' => 'required',
            'password_new' => 'required',
        ]);

        $admin = Admin::where('email', $email)->first();

        if($admin && password_verify($request->password_old, $admin->password)) {
            if ($request->password_old === $request->password_new || $request->password_new == "12345678") {
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Ubah Password Gagal',
                    'message' => "Password baru tidak boleh sama dengan yang lama",
                ]);
            } else {
                $admin->update([
                    'password' => bcrypt($request->password_new),
                    'password_reset' => 0,
                ]);
                Session::flash('alert', [
                    'type' => 'success',
                    'title' => 'Ubah Password Berhasil',
                    'message' => "",
                ]);
                if($admin->role == "admin_sekolah") {
                    return redirect()->route("pelajar");
                } else {
                    return redirect()->route("dashboard");
                }
            }
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Ubah Password Gagal',
                'message' => "Mohon dicek kembali inputannya!",
            ]);
        }

        return back();
    }

    public function viewModal(Request $request) { // MODAL
        if($request->platNomor) {
            $bus = Bus::where('plat_nomor', $request->platNomor)->first();
            if($bus) {
                $today = Carbon::today()->format('Y-m-d');
                $monitoringBus = MonitoringBus::where('plat_nomor', $bus->plat_nomor)->where('created_at', '>', $today)->latest()->first();
                if($monitoringBus) {
                    $jmlPenumpang = $bus->jumlah_kursi - $monitoringBus->sisa_pnp;
                    $timestamp = strtotime($monitoringBus->updated_at);
                    $dateWIB = date('d F Y H:i:s T', $timestamp);

                    $modal = '
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Informasi Detail '.$request->platNomor.'</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="w-100">
                                    <tr>
                                        <td style="vertical-align: top; font-weight: bold;">Rute</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">'.$bus->rute_awal.' - '.$bus->rute_akhir.'</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top; font-weight: bold;">Latitude</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">'.$monitoringBus->latitude.'</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top; font-weight: bold;">Longitude</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">'.$monitoringBus->longitude.'</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top; font-weight: bold;">PNP</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">'.$jmlPenumpang.' dari '.$bus->jumlah_kursi.'</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top; font-weight: bold;">Sisa</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">'.$monitoringBus->sisa_pnp.' kursi</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top; font-weight: bold;">Last Update</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">'.$dateWIB.'</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top; font-weight: bold;">Lokasi</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;"><a href="https://maps.google.com/maps?q='.$monitoringBus->latitude.','.$monitoringBus->longitude.'" target="_blank">Lihat lokasi di Google Maps</a></td>
                                    </tr>
                                </table><hr>
                                <h1 class="modal-title fs-5">List Pelajar di Dalam Bus</h1>
                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered table-striped table-vcenter" id="pelajar-table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Waktu</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                    $monitorings = Monitoring::where('plat_nomor', $request->platNomor)->where('status', 'in')->where('created_at', '>', $today)->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                              ->from('monitoring as m2')
                              ->whereRaw('m2.nisn = monitoring.nisn')
                              ->where('status', 'out')
                              ->whereRaw('m2.created_at > monitoring.created_at');
                    })->latest()->get();
                    $counter = 1;
                    foreach ($monitorings as $monitoring) {
                        $pelajar = Pelajar::where('nisn', $monitoring->nisn)->first();
                        $timestamp2 = strtotime($monitoring->created_at);
                        $dateWIB2 = date('d F Y H:i:s T', $timestamp2);
                        if ($pelajar) {
                            $modal .= '
                                            <tr>
                                                <td>'.$counter++.'</td>
                                                <td>'.$pelajar->nama.'</td>
                                                <td>'.$dateWIB2.'</td>
                                            </tr>
                            ';
                        }
                    }
                    $modal .= '         </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    ';
                } else {
                    $modal = '
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Informasi Detail '.$request->platNomor.'</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="w-100">
                                    <tr>
                                        <td style="vertical-align: top; font-weight: bold;">Latitude</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">-</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top; font-weight: bold;">Longitude</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">-</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top; font-weight: bold;">PNP</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">-</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top; font-weight: bold;">Sisa</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">-</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top; font-weight: bold;">Last Update</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">-</td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align: top; font-weight: bold;">Lokasi</td>
                                        <td style="vertical-align: top;">:</td>
                                        <td style="vertical-align: top;">-</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    ';
                }
                return response()->json([
                    'modal' => $modal,
                ]);
            }
        }
    }
}
