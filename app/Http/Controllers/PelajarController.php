<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\Pelajar;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PelajarController extends Controller
{
    public function index() {
        $kode_sekolah = Sekolah::where('email_admin_sekolah', Auth::user()->email)->first()->kode_sekolah;
        $pelajars = Pelajar::where('kode_sekolah', $kode_sekolah)->get();
        $sekolahs = Sekolah::where('kode_sekolah', $kode_sekolah)->first();
        return view('manajemen.pelajar.main', compact('kode_sekolah', 'pelajars', 'sekolahs'));
    }

    public function create() {
        $kode_sekolah = Sekolah::where('email_admin_sekolah', Auth::user()->email)->first()->kode_sekolah;
        $sekolahs = Sekolah::where('kode_sekolah', $kode_sekolah)->first();
        return view('manajemen.pelajar.create', compact('kode_sekolah', 'sekolahs'));
    }

    public function store(Request $request) {
        $kode_sekolah = Sekolah::where('email_admin_sekolah', Auth::user()->email)->first()->kode_sekolah;
        $request->validate([
            'pelajar.*.*' => 'required',
        ]);
        foreach ($request->pelajar as $value) {
            $value['kode_sekolah'] = $kode_sekolah;
            $value['password'] = bcrypt("12345678");
            Pelajar::create($value);
        }
        Session::flash('alert', [
            'type' => 'success',
            'title' => 'Input Data Berhasil',
            'message' => "",
        ]);
        return redirect()->route("pelajar");
    }

    public function update(Request $request, $nisn) {
        $this->validate($request, [
            'pelajar_uid' => 'required',
            'pelajar_nama' => 'required',
            'pelajar_jk' => 'required',
            'pelajar_nohp' => 'required',
            'pelajar_alamat' => 'required',
        ]);
        $pelajar = Pelajar::where('nisn', $nisn)->first();
        $pelajar->update([
            'uid' => $request->pelajar_uid,
            'nama' => $request->pelajar_nama,
            'jk' => $request->pelajar_jk,
            'nohp' => $request->pelajar_nohp,
            'alamat' => $request->pelajar_alamat,
        ]);
        Session::flash('alert', [
            'type' => 'success',
            'title' => 'Edit Data Berhasil',
            'message' => "",
        ]);
        return back();
    }

    public function destroy($nisn) {
        Pelajar::findOrFail($nisn)->delete();
        Session::flash('alert', [
            'type' => 'success',
            'title' => 'Hapus Data Berhasil',
            'message' => "",
        ]);
        return back();
    }

    public function cekUid(Request $request) { // AJAX
        if(is_numeric($request->uid)) {
            $result = Pelajar::where('uid', $request->uid)->exists();
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Kode UID sudah terdaftar',
                    'uidExists' => true,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kode UID tersedia',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Kode UID harus Angka!',
            ]);
        }
    }

    public function cekNisn(Request $request) { // AJAX
        if(is_numeric($request->nisn)) {
            $result = Pelajar::where('nisn', $request->nisn)->exists();
            if ($result) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'NISN sudah terdaftar',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'NISN tersedia',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'NISN harus Angka!',
            ]);
        }
    }

    public function viewModal(Request $request) { // MODAL
        if(is_numeric($request->nisn)) {
            $pelajar = Pelajar::where('nisn', $request->nisn)->first();
            if($pelajar) {
                $modal = '
                    <form id="form" method="POST" action="'.route("pelajar.update", ['nisn' => $pelajar->nisn]).'">
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
                                        <div class="input-group" id="ig-uid">
                                            <div class="form-floating">
                                                <input type="text" class="form-control form-control-alt form-control-lg" pattern="[0-9]+" name="pelajar_uid" placeholder=" " value="'.$pelajar->uid.'" autocomplete="off" required>
                                                <label>Kode UID <small class="text-danger">*</small></label>
                                            </div>
                                            <span class="input-group-text">
                                                <i class="fa fa-check-circle text-success" id="uid-available"></i>
                                                <i class="fa fa-exclamation-circle text-danger i-notavailable" id="uid-notAvailable"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control form-control-alt form-control-lg" name="pelajar_nama" placeholder=" " value="'.$pelajar->nama.'" autocomplete="off" required>
                                            <label>Nama <small class="text-danger">*</small></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md mb-3">
                                        <div class="form-floating">
                                            <select class="form-select form-control form-control-alt form-control-lg" name="pelajar_jk" required>
                                                <option value="'.$pelajar->jk.'" hidden selected>'.($pelajar->jk == "p" ? "Perempuan" : "Laki-laki").'</option>
                                                <option value="l">Laki-laki</option>
                                                <option value="p">Perempuan</option>
                                            </select>
                                            <label>Jenis Kelamin <small class="text-danger">*</small></label>
                                        </div>
                                    </div>
                                    <div class="col-md mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control form-control-alt form-control-lg" name="pelajar_nohp" pattern="[0-9]+" placeholder=" " value="'.$pelajar->nohp.'" autocomplete="off" required>
                                            <label>No. HP Orang Tua <small class="text-danger">*</small></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md mb-3">
                                        <div class="form-floating">
                                            <textarea class="form-control form-control-alt form-control-lg" name="pelajar_alamat" placeholder=" " style="height: 100px" required>'.$pelajar->alamat.'</textarea>
                                            <label>Alamat <small class="text-danger">*</small></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="btn-submit"><i class="fa fa-save"></i> Submit</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-x"></i> Batal</button>
                            </div>
                        </div>
                    </form>
                ';
            }
        }
        return response()->json([
            'modal' => $modal,
        ]);
    }

    // -- ANDROID --
    public function loginNISN(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nisn' => 'required|numeric',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'NISN / Password tidak valid',
            ], 401);
        }

        $nisn = $request->nisn;
        $password = $request->password;

        $pelajar = Pelajar::where('nisn', $nisn)->first();

        if (!$pelajar) {
            return response()->json([
                'success' => false,
                'message' => 'NISN tidak terdaftar',
            ], 401);
        }

        if (!password_verify($password, $pelajar->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah',
            ], 401);
        }

        $sekolah = Sekolah::where('kode_sekolah', $pelajar->kode_sekolah)->first();

        // Login berhasil
        return response()->json([
            'success' => true,
            'message' => $pelajar->nama,
            'sekolah' => $sekolah->nama_sekolah,
        ], 200);
    }

    public function updatePassword(Request $request) {
        $this->validate($request, [
            'nisn' => 'required|numeric',
            'password_old' => 'required',
            'password_new' => 'required',
        ]);

        $pelajar = Pelajar::where('nisn', $request->nisn)->first();

        if($pelajar && password_verify($request->password_old, $pelajar->password)) {
            if ($request->password_old === $request->password_new || $request->password_new == "12345678") {
                return response()->json([
                    'success' => false,
                    'message' => 'NISN / Password tidak valid',
                ], 401);
            } else {
                $pelajar->update([
                    'password' => bcrypt($request->password_new),
                    'password_reset' => 0,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => $pelajar->nama,
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'NISN / Password tidak valid',
            ], 401);
        }

        return back();
    }

    public function resetPassword($nisn) {
        $pelajar = Pelajar::where("nisn", $nisn)->first();

        $pelajar->update([
            'password' => bcrypt("12345678"),
            'password_reset' => 1,
        ]);

        Session::flash('alert', [
            'type' => 'success',
            'title' => 'Reset Password Berhasil',
            'message' => "",
        ]);

        return back();
    }
}
