<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login(Request $request) {
        $data = [
            "email" => $request->login_email,
            "password" => $request->login_password,
        ];

        if(Auth::attempt($data)) {
            Session::flash('alert', [
                // tipe dalam sweetalert2: success, error, warning, info, question
                'type' => 'success',
                'title' => 'Login Berhasil',
                'message' => "Selamat Datang ".Auth::user()->nama,
            ]);
            if (Auth::user()->password_reset) {
                return redirect()->route("user.viewUbahPassword");
            }
            return redirect()->route("dashboard");
        }
        Session::flash('alert', [
            'type' => 'error',
            'title' => 'Login Gagal',
            'message' => "Email atau Password salah!",
        ]);
        return back();
    }

    public function logoutaksi() {
        Auth::logout();
        Session::flash('alert', [
            'type' => 'success',
            'title' => 'Logout Berhasil',
            'message' => "",
        ]);
        return redirect()->route("beranda");
    }

    public function viewModal() {
        $modal = '
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Masuk</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="'.route("login").'">
                    '.csrf_field().'
                    <div class="modal-body">
                        <div class="py-3">
                            <div class="form-floating mb-4">
                                <input type="email" class="form-control form-control-alt form-control-lg" name="login_email" placeholder=" " autocomplete="off" required>
                                <label>Email</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control form-control-alt form-control-lg" name="login_password" placeholder=" " autocomplete="off" required>
                                <label>Password</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn w-100 btn-alt-primary">
                            <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> Sign In
                        </button>
                    </div>
                </form>
            </div>
        ';
        return response()->json([
            'modal' => $modal,
        ]);
    }
}
