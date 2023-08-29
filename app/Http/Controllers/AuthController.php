<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Return_;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);

        if (auth()->attempt($credentials)) {
            $token = Auth::guard('api')->attempt($credentials);

            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil',
                'token' => $token
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau Password salah'
        ]);
    }

    // public function login(Request $request)
    // {
    //     $this->validate($request, [
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $credentials = request(['email', 'password']);

    //     if (auth()->attempt($credentials)) {
    //         // Jika pengguna sudah login sebelumnya (sesi login masih ada),
    //         // arahkan langsung ke halaman dashboard
    //         if (Auth::check()) {
    //             return redirect()->route('dashboard');
    //         }

    //         // Jika pengguna baru saja login, berikan respon JSON seperti yang Anda lakukan sebelumnya
    //         $token = Auth::guard('api')->attempt($credentials);
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Login Berhasil',
    //             'token' => $token
    //         ]);
    //     }

    //     // Jika login gagal, berikan respon JSON seperti yang Anda lakukan sebelumnya
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Email atau Password salah'
    //     ]);
    // }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_member' => 'required',
            'provinsi' => 'required',
            'kabupaten_kota' => 'required',
            'kecamatan' => 'required',
            'detail_alamat' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email',
            'password' => 'required|same:konfirmasi_password',
            'konfirmasi_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();
        $input['password'] = bcrypt($request->password);
        unset($input['konfirmasi_password']);
        $member = Member::create($input);

        return response()->json([
            'data' => $member
        ]);
    }

    public function login_member(Request $request)
    {
        return view('auth.login_member');
    }

    public function login_member_action(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            Session::flash('errors', $validator->errors()->toArray());
            return redirect('/login_member');
        }

        $credentials = $request->only('email', 'password');
        $member = Member::where('email', $request->email)->first();

        if ($member) {
            if (Auth::guard('webmember')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect('/');
            } else {
                Session::flash('failed', "Password salah");
                return redirect('/login_member');
            }
        } else {
            Session::flash('failed', "Email Tidak ditemukan");
            return redirect('/login_member');
        }
    }

    public function register_member(Request $request)
    {
        return view('auth.register_member');
    }

    public function register_member_action(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama_member' => 'required',
            'no_hp' => 'required',
            'email' => 'required|email',
            'password' => 'required|same:konfirmasi_password',
            'konfirmasi_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            Session::flash('errors', $validator->errors()->toArray());
            return redirect('/register_member');
        }

        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        unset($input['konfirmasi_password']);
        Member::create($input);

        Session::flash('success', 'Account successfully created!');
        return redirect('/login_member');
    }
    // public function register_member_action(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'no_hp' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required|same:konfirmasi_password',
    //         'konfirmasi_password' => 'required|same:password'
    //     ]);

    //     // Tambahkan validasi khusus untuk nama_member
    //     if (!$request->filled('nama_member')) {
    //         // Jika 'nama_member' tidak ada atau kosong
    //         $validator->errors()->add('nama_member', 'The nama member field is required.');
    //     }

    //     if ($validator->fails()) {
    //         Session::flash('errors', $validator->errors()->toArray());
    //         return redirect('/register_member');
    //     }

    //     $input = $request->all();
    //     $input['password'] = Hash::make($request->password);
    //     unset($input['konfirmasi_password']);
    //     Member::create($input);

    //     Session::flash('success', 'Account successfully created!');
    //     return redirect('/login_member');
    // }


    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }

    public function logout_member()
    {
        Auth::guard('webmember')->logout();
        Session::flush();
        return redirect('/');
    }


    // public function forgot_password(Request $request)
    // {
    //     return view('auth.forgot_password');
    // }

    // public function sendResetLinkEmail(Request $request)
    // {
    //     $request->validate(['email' => 'required|email']);

    //     $response = Password::broker('members')->sendResetLink(
    //         $request->only('email')
    //     );

    //     return $response == Password::RESET_LINK_SENT
    //         ? back()->with(['status' => __($response)])
    //         : back()->withErrors(['email' => __($response)]);
    // }

    // public function showResetForm($token)
    // {
    //     return view('auth.reset_password', ['token' => $token]);
    // }

    // public function resetMemberPassword(Request $request)
    // {
    //     $request->validate([
    //         'token' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required|confirmed',
    //     ]);
    //     if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $request->password)) {
    //         return back()->withErrors(['password' => ['The password must contain at least one uppercase letter, one lowercase letter, and one number.']]);
    //     }

    //     $status = Password::reset(
    //         $request->only('email', 'password', 'password_confirmation', 'token'),
    //         function ($member, $password) {
    //             // Cari anggota (member) berdasarkan alamat email
    //             $member = Member::where('email', $member->email)->first();

    //             // Jika anggota (member) tidak ditemukan
    //             if (!$member) {
    //                 return back()->withErrors(['email' => ['Invalid email']]);
    //             }

    //             // Reset password anggota (member) dan simpan
    //             $member->forceFill([
    //                 'password' => Hash::make($password)
    //             ])->setRememberToken(Str::random(60));

    //             $member->save();

    //             // Picu event PasswordReset untuk memberi tahu bahwa password telah direset
    //             event(new PasswordReset($member));
    //         }
    //     );

    //     return $status === Password::PASSWORD_RESET
    //                 ? redirect()->route('login_member')->with('status', __($status))
    //                 : back()->withErrors(['email' => [__($status)]]);
    // }

}
