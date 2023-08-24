<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    public function index() {
        $datas = Admin::join('sekolah', 'admin.email', '=', 'sekolah.email_admin_sekolah')->select('admin.*', 'sekolah.*')->get();
        return view('manajemen.sekolah.main', compact('datas'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'sekolah_kodeSekolah' => 'required',
            'sekolah_namaSekolah' => 'required',
            'sekolah_alamatSekolah' => 'required',
            'sekolah_latlongSekolah' => 'required',
            'sekolah_namaAdminSekolah' => 'required',
            'sekolah_emailAdminSekolah' => 'required|email',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!!',
            ]);
        } else {
            $result = Sekolah::where('kode_sekolah', $request->sekolah_kodeSekolah)->exists();
            if($result) {
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Input Data Gagal',
                    'message' => 'Data Sekolah telah terdaftar!',
                ]);
            } else {
                Sekolah::create([
                    "kode_sekolah" => $request->sekolah_kodeSekolah,
                    "nama_sekolah" => $request->sekolah_namaSekolah,
                    "latlong_sekolah" => $request->sekolah_latlongSekolah,
                    "alamat_sekolah" => $request->sekolah_alamatSekolah,
                    "nama_admin_sekolah" => $request->sekolah_namaAdminSekolah,
                    "email_admin_sekolah" => $request->sekolah_emailAdminSekolah,
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

    public function update(Request $request, $kode_sekolah) {
        $validator = Validator::make($request->all(), [
            'sekolah_namaSekolah' => 'required',
            'sekolah_alamatSekolah' => 'required',
            'sekolah_latlongSekolah' => 'required',
            'sekolah_namaAdminSekolah' => 'required',
            'sekolah_emailAdminSekolah' => 'required|email',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!!',
            ]);
        } else {
            $sekolah = Sekolah::findOrFail($kode_sekolah);
            if($sekolah) {
                $admin = Admin::where('email', $sekolah->email_admin_sekolah)->first();
                $admin->update([
                    "email" => $request->sekolah_emailAdminSekolah,
                ]);
                $sekolah->update([
                    "nama_sekolah" => $request->sekolah_namaSekolah,
                    "latlong_sekolah" => $request->sekolah_latlongSekolah,
                    "alamat_sekolah" => $request->sekolah_alamatSekolah,
                    "nama_admin_sekolah" => $request->sekolah_namaAdminSekolah,
                    "email_admin_sekolah" => $request->sekolah_emailAdminSekolah,
                ]);
                Session::flash('alert', [
                    'type' => 'success',
                    'title' => 'Edit Data Berhasil',
                    'message' => "",
                ]);
            } else {
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Input Data Gagal',
                    'message' => "Mohon dicek kembali inputannya!",
                ]);
            }
        }
        return back();
    }

    public function destroy($kode_sekolah) {
        $sekolah = Sekolah::findOrFail($kode_sekolah);
        if($sekolah) {
            $sekolah->delete();
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Hapus Data Berhasil',
                'message' => "",
            ]);
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Edit Data Gagal',
                'message' => "ID tidak valid!",
            ]);
        }
        return back();
    }

    public function cekKodeSekolah(Request $request) { // AJAX
        $result = Sekolah::where('kode_sekolah', $request->kode_sekolah)->exists();
        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kode Sekolah sudah terdaftar',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode Sekolah tersedia',
            ]);
        }
    }

    public function cekEmail(Request $request) { // AJAX
        $result = Admin::where('email', $request->email)->exists();
        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'E-Mail sudah terdaftar',
                'emailExists' => true,
            ]);
        } else {
            if (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]{2,}(?:\.[a-zA-Z]{1}[a-zA-Z]{1,})$/', $request->email)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'E-Mail tersedia',
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Format E-Mail tidak valid',
                ]);
            }
        }
    }

    public function viewModal(Request $request) { // MODAL
        $sekolah = Sekolah::where('kode_sekolah', $request->kode_sekolah)->first();
        if($sekolah) {
            $modal = '
                <form id="form" method="POST" action="'.route("sekolah.update", ['kode_sekolah' => $sekolah->kode_sekolah]).'">
                    '.csrf_field().'
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Form Edit Data</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-alt form-control-lg" name="sekolah_namaSekolah" placeholder=" " value="'.$sekolah->nama_sekolah.'" autocomplete="off" required>
                                        <label>Nama Sekolah <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-alt form-control-lg" name="sekolah_latlongSekolah" placeholder=" " value="'.$sekolah->latlong_sekolah.'" autocomplete="off" required>
                                        <label>Titik Lokasi Sekolah <span class="small fst-italic">(Lat,Long)</span> <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <textarea class="form-control form-control-alt form-control-lg" name="sekolah_alamatSekolah" placeholder=" " style="height: 100px" required>'.$sekolah->alamat_sekolah.'</textarea>
                                        <label>Alamat Sekolah <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-alt form-control-lg" name="sekolah_namaAdminSekolah" placeholder=" " value="'.$sekolah->nama_admin_sekolah.'" autocomplete="off" required>
                                        <label>Nama Admin Sekolah <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                                <div class="col-md mb-3">
                                    <div class="input-group" id="ig-email">
                                        <div class="form-floating">
                                            <input type="email" class="form-control form-control-alt form-control-lg" name="sekolah_emailAdminSekolah" placeholder=" " value="'.$sekolah->email_admin_sekolah.'" autocomplete="off" required>
                                            <label>E-Mail Admin <small class="text-danger">*</small></label>
                                        </div>
                                        <span class="input-group-text">
                                            <i class="fa fa-check-circle text-success" id="email-available"></i>
                                            <i class="fa fa-exclamation-circle text-danger i-notavailable" id="email-notAvailable"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <small class="fst-italic"><span class="text-danger">*</span> Wajib Diisi</small><br>
                            <small class="fst-italic">Contoh format Lat,Long = -7.6473089,111.5265171</small><br>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="btn-submit"><i class="fa fa-save"></i> Submit</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-x"></i> Batal</button>
                        </div>
                    </div>
                </form>
            ';
        } else {
            $modal = '
                <form id="form" method="POST" action="'.route("sekolah.store").'">
                    '.csrf_field().'
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Form Tambah Data</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md mb-3">
                                    <div class="input-group" id="ig-kodeSekolah">
                                        <div class="form-floating">
                                            <input type="text" class="form-control form-control-alt form-control-lg" name="sekolah_kodeSekolah" placeholder=" " autocomplete="off" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required>
                                            <label>Kode Sekolah <small class="text-danger">*</small></label>
                                        </div>
                                        <span class="input-group-text">
                                            <i class="fa fa-check-circle text-success" id="available"></i>
                                            <i class="fa fa-exclamation-circle text-danger i-notavailable" id="notAvailable"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-alt form-control-lg" name="sekolah_namaSekolah" placeholder=" " autocomplete="off" required>
                                        <label>Nama Sekolah <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <textarea class="form-control form-control-alt form-control-lg" name="sekolah_alamatSekolah" placeholder=" " style="height: 100px" required></textarea>
                                        <label>Alamat Sekolah <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-alt form-control-lg" name="sekolah_latlongSekolah" placeholder=" " autocomplete="off" required>
                                        <label>Titik Lokasi Sekolah <span class="small fst-italic">(Lat,Long)</span> <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-alt form-control-lg" name="sekolah_namaAdminSekolah" placeholder=" " autocomplete="off" required>
                                        <label>Nama Admin Sekolah <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                                <div class="col-md mb-3">
                                    <div class="input-group" id="ig-email">
                                        <div class="form-floating">
                                            <input type="email" class="form-control form-control-alt form-control-lg" name="sekolah_emailAdminSekolah" placeholder=" " autocomplete="off" required>
                                            <label>E-Mail Admin <small class="text-danger">*</small></label>
                                        </div>
                                        <span class="input-group-text">
                                            <i class="fa fa-check-circle text-success" id="email-available"></i>
                                            <i class="fa fa-exclamation-circle text-danger i-notavailable" id="email-notAvailable"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <small class="fst-italic"><span class="text-danger">*</span> Wajib Diisi</small><br>
                            <small class="fst-italic">Contoh format Lat,Long = -7.6473089,111.5265171</small><br>
                            <small class="fst-italic">Password default admin sekolah: 12345678</small>
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
            'kode_sekolah' => isset($request->kode_sekolah) ? $request->kode_sekolah : "",
            'modal' => $modal,
        ]);
    }
}
