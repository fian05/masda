<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        $admins = Admin::where('role', '!=', 'admin_sekolah')->get();
        return view('manajemen.admin.main', compact('admins'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'admin_name' => 'required',
            'admin_email' => 'required|email',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            $result = Admin::where('email', $request->admin_email)->exists();
            if ($result) {
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Input Data Gagal',
                    'message' => 'E-Mail telah terdaftar!',
                ]);
            } else {
                Admin::create([
                    "nama" => $request->admin_name,
                    "role" => "admin",
                    "email" => $request->admin_email,
                    "password" => bcrypt("12345678"),
                ]);
        
                Session::flash('alert', [
                    // tipe dalam sweetalert2: success, error, warning, info, question
                    'type' => 'success',
                    'title' => 'Input Data Berhasil',
                    'message' => "",
                ]);
            }
        }
        return back();
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'admin_name' => 'required',
            'admin_email' => 'required|email',
        ]);
        if ($validator->fails()) {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Input Data Gagal',
                'message' => 'Ada inputan yang salah!',
            ]);
        } else {
            $admin = Admin::findOrFail($id);
            if($admin) {
                $admin->update([
                    'nama' => $request->admin_name,
                    'email' => $request->admin_email,
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
                    'message' => "ID tidak valid!",
                ]);
            }
        }
        return back();
    }

    public function destroy($id) {
        $admin = Admin::findOrFail($id);
        if($admin) {
            if ($admin->role === 'super') { // memeriksa role admin
                Session::flash('alert', [
                    'type' => 'error',
                    'title' => 'Hapus Data Gagal',
                    'message' => "Anda tidak diizinkan menghapus Super Admin!",
                ]);
            } else {
                $admin->delete();
                Session::flash('alert', [
                    'type' => 'success',
                    'title' => 'Hapus Data Berhasil',
                    'message' => "",
                ]);
            }
        } else {
            Session::flash('alert', [
                'type' => 'error',
                'title' => 'Edit Data Gagal',
                'message' => "ID tidak valid!",
            ]);
        }
        return back();
    }

    public function resetPassword($id) {
        $admin = Admin::findOrFail($id);
        if($admin) {
            $admin->update([
                'password' => bcrypt("12345678"),
                'password_reset' => 1,
            ]);
            Session::flash('alert', [
                'type' => 'success',
                'title' => 'Reset Password Berhasil',
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

    public function cekEmail(Request $request) { // AJAX
        $result = Admin::where('email', $request->email)->exists();
        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'E-Mail sudah terdaftar',
                'emailExists' => true,
            ]);
        } else {
            // if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) { bawaan PHP
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
        if(is_numeric($request->id)) {
            $admin = Admin::findOrFail($request->id);
            if($admin) { // Edit
                $modal = '
                    <form id="form" method="POST" action="'.route("admin.update", ['id' => $admin->id]).'">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="PUT">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Form Edit Data Admin</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control form-control-alt form-control-lg" name="admin_name" placeholder=" " value="'.($admin->nama).'" autocomplete="off" required>
                                            <label>Nama <small class="text-danger">*</small></label>
                                        </div>
                                    </div>
                                    <div class="col-md mb-3">
                                        <div class="input-group" id="ig-email">
                                            <div class="form-floating">
                                                <input type="email" class="form-control form-control-alt form-control-lg" name="admin_email" placeholder=" " value="'.($admin->email).'" autocomplete="off" required>
                                                <label>Email <small class="text-danger">*</small></label>
                                            </div>
                                            <span class="input-group-text">
                                                <i class="fa fa-check-circle text-success" id="available"></i>
                                                <i class="fa fa-exclamation-circle text-danger i-notavailable" id="notAvailable"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <small class="fst-italic"><span class="text-danger">*</span> Wajib Diisi</small>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="btn-submit"><i class="fa fa-save"></i> Submit</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-x"></i> Batal</button>
                            </div>
                        </div>
                    </form>
                ';
            }
        } else { // Tambah
            $modal = '
                <form id="form" method="POST" action="'.route("admin.store").'">
                    '.csrf_field().'
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5">Form Tambah Data Admin</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-alt form-control-lg" name="admin_name" placeholder=" " autocomplete="off" required>
                                        <label>Nama <small class="text-danger">*</small></label>
                                    </div>
                                </div>
                                <div class="col-md mb-3">
                                    <div class="input-group" id="ig-email">
                                        <div class="form-floating">
                                            <input type="email" class="form-control form-control-alt form-control-lg" name="admin_email" placeholder=" " autocomplete="off" required>
                                            <label>E-Mail <small class="text-danger">*</small></label>
                                        </div>
                                        <span class="input-group-text">
                                            <i class="fa fa-check-circle text-success" id="available"></i>
                                            <i class="fa fa-exclamation-circle text-danger i-notavailable" id="notAvailable"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <small class="fst-italic"><span class="text-danger">*</span> Wajib Diisi</small><br>
                            <small class="fst-italic">Password default admin: 12345678</small>
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
            'id' => isset($request->id) ? $request->id : "",
            'modal' => $modal,
        ]);
    }
}
