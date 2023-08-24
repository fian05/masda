<?php

namespace App\Http\Controllers;

use App\Models\Bis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BisController extends Controller
{
    public function index() {
        $buses = Bis::all();
        return view('manajemen.bus.main', compact('buses'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'bus_platNomor' => 'required',
            'bus_ruteAwal' => 'required',
            'bus_ruteAkhir' => 'required',
            'bus_jumlahPnp' => 'required|integer',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Inputan ada yang salah!!',
            ]);
        } else {
            $result = Bis::where('plat_nomor', $request->bus_platNomor)->exists();
            if($result) {
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Input Data Gagal',
                    'message' => 'Data Bis telah terdaftar!',
                ]);
            } else {
                Bis::create([
                    "plat_nomor" => $request->bus_platNomor,
                    "rute_awal" => $request->bus_ruteAwal,
                    "rute_akhir" => $request->bus_ruteAkhir,
                    "jumlah_kursi" => $request->bus_jumlahPnp,
                ]);
                Session::flash('alert', [
                    'type' => 'success',
                    'title' => 'Input Data Berhasil',
                    'message' => "",
                ]);
            }
        }
        return back();
    }

    public function update(Request $request, $platNomor) {
        $validator = Validator::make($request->all(), [
            'bus_ruteAwal' => 'required',
            'bus_ruteAkhir' => 'required',
            'bus_jumlahPnp' => 'required|integer',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!!',
            ]);
        } else {
            $result = Bis::where('plat_nomor', $platNomor)->exists();
            if($result) {
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Edit Data Gagal',
                    'message' => "Data Bis tidak valid!",
                ]);
            }
            $bus = Bis::findOrFail($platNomor);
            if($bus) {
                $bus->update([
                    'rute_awal' => $request->bus_ruteAwal,
                    'rute_akhir' => $request->bus_ruteAkhir,
                    'jumlah_kursi' => $request->bus_jumlahPnp,
                ]);
                Session::flash('alert', [
                    'type' => 'success',
                    'title' => 'Edit Data Berhasil',
                    'message' => "",
                ]);
            } else {
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Edit Data Gagal',
                    'message' => "Data Bis tidak valid!",
                ]);
            }
        }
        return back();
    }

    public function destroy($plat_nomor) {
        $bus = Bis::findOrFail($plat_nomor);
        if($bus) {
            $bus->delete();
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Hapus Data Berhasil',
                'message' => "",
            ]);
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Edit Data Gagal',
                'message' => "Data Bis tidak valid!",
            ]);
        }
        return back();
    }

    public function cekPlatNomor(Request $request) { // AJAX
        $result = Bis::where('plat_nomor', $request->platNomor)->exists();
        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => "Plat Nomor Sudah Ada",
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => "Plat Nomor Tersedia",
            ]);
        }
    }

    public function viewModal(Request $request) { // MODAL
        $bus = Bis::where('plat_nomor', $request->platNomor)->first();
        if($bus) { // Edit
            $modal = '
                <form id="form" method="POST" action="'.route("bus.update", ['plat_nomor' => $bus->plat_nomor] ).'">
                    '.csrf_field().'
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Form Edit Data '.preg_replace('/(\d+)/', ' $1 ', $bus->plat_nomor).'</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-alt form-control-lg" name="bus_ruteAwal" placeholder=" " value="'.$bus->rute_awal.'" autocomplete="off" required>
                                        <label>Lokasi Awal <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-alt form-control-lg" name="bus_ruteAkhir" placeholder=" " value="'.$bus->rute_akhir.'" autocomplete="off" required>
                                        <label>Lokasi Akhir <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <input type="number" class="form-control form-control-alt form-control-lg" name="bus_jumlahPnp" pattern="[0-9]+" min="1" placeholder=" " value="'.$bus->jumlah_kursi.'" autocomplete="off" required>
                                        <label>Jumlah Kursi <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                            </div>
                            <small class="fst-italic"><span class="text-danger">*</span> Wajib Diisi</small><br>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="btn-submit"><i class="fa fa-save"></i> Submit</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-x"></i> Batal</button>
                        </div>
                    </div>
                </form>
            ';
        } else { // Tambah
            $modal = '
                <form id="form" method="POST" action="'.route("bus.store").'">
                    '.csrf_field().'
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Form Tambah Data</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md mb-3">
                                    <div class="input-group" id="ig-platNomor">
                                        <div class="form-floating">
                                            <input type="text" class="form-control form-control-alt form-control-lg" name="bus_platNomor" placeholder=" " autocomplete="off" maxlength="15" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required>
                                            <label>Plat Nomor <small class="text-danger">*</small></label>
                                        </div>
                                        <span class="input-group-text">
                                            <i class="fa fa-check-circle text-success" id="available"></i>
                                            <i class="fa fa-exclamation-circle text-danger i-notavailable" id="notAvailable"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <input type="number" class="form-control form-control-alt form-control-lg" name="bus_jumlahPnp" pattern="[0-9]+" min="1" placeholder=" " autocomplete="off" required>
                                        <label>Jumlah Kursi <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-alt form-control-lg" name="bus_ruteAwal" placeholder=" " autocomplete="off" required>
                                        <label>Lokasi Awal <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-alt form-control-lg" name="bus_ruteAkhir" placeholder=" " autocomplete="off" required>
                                        <label>Lokasi Akhir <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                            </div>
                            <small class="fst-italic"><span class="text-danger">*</span> Wajib Diisi</small><br>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="btn-submit"><i class="fa fa-save"></i> Submit</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-x"></i> Batal</button>
                        </div>
                    </div>
                </form>
            ';
        }
        return response()->json([
            'platNomor' => isset($request->platNomor) ? $request->platNomor : "",
            'modal' => $modal,
        ]);
    }
}
