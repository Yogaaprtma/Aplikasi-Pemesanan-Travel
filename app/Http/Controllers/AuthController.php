<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'no_telp' => 'required|string',
            'alamat' => 'required|string|max:255',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register.page')
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole('user');

        if ($user) {
            Auth::login($user);
            return redirect()->route('login.page')->with('success', 'Register success');
        } else {
            return redirect()->route('register.page')->with('error', 'Register failed');
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('login.page')
                    ->withErrors($validator)
                    ->withInput();
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                return redirect()->route('home.admin')->with('success', 'Login success sebagai admin');
            } else {
                return redirect()->route('home.penumpang')->with('success', 'Login success sebagai customer');
            } 
        } else {
            return back()->withErrors(['email' => 'Email atau password salah']);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.page');
    }
}