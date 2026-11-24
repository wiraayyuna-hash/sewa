<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller {
  function loginForm() { return view('auth.login'); }
  function registerForm() { return view('auth.register'); }
  function login(Request $r) {
    if (Auth::attempt($r->only('email','password'), true)) {
      $r->session()->regenerate();
      return redirect(Auth::user()->role === 'partner' ? '/partner' : '/katalog');
    }
    return back()->withErrors(['email'=>'Email atau password salah'])->onlyInput('email');
  }
  function register(Request $r) {
    $d = $r->validate(['name'=>'required','email'=>'required|email|unique:users','password'=>'required|min:6','phone'=>'required','category'=>'required']);
    Auth::login(User::create($d)); return redirect('/');
  }
  function logout(Request $r) { Auth::logout(); $r->session()->invalidate(); return redirect('/'); }
}
