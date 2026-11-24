<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{PartnerController as P, StoreController as S, AuthController as A};

Route::get('/', fn() => redirect(auth()->check() && auth()->user()->role !== 'partner' ? '/katalog' : '/partner'));
Route::get('/katalog', [S::class,'home'])->name('home');
Route::get('/kendaraan/{vehicle}', [S::class,'show'])->name('vehicle');
Route::get('/login', [A::class,'loginForm'])->name('login');
Route::post('/login', [A::class,'login']);
Route::get('/daftar', [A::class,'registerForm']);
Route::post('/daftar', [A::class,'register']);
Route::post('/logout', [A::class,'logout'])->name('logout');

Route::middleware('auth')->group(function () {
  Route::post('/kendaraan/{vehicle}/booking', [S::class,'book'])->name('book');
  Route::get('/booking-saya', [S::class,'mine'])->name('mine');
  Route::post('/booking/{booking}/bayar', [S::class,'pay'])->name('pay');
  Route::post('/booking/{booking}/ulasan', [S::class,'review'])->name('review');
});

Route::prefix('partner')->name('p.')->middleware(['auth'])->group(function () {
    Route::get('/', [P::class, 'dashboard'])->name('dashboard');
    Route::get('/armada', [P::class, 'fleet'])->name('fleet');
    Route::post('/armada', [P::class, 'vehicleStore'])->name('vstore');
    Route::post('/armada/tarif', [P::class, 'fee'])->name('fee');
    Route::post('/armada/{vehicle}', [P::class, 'vehicleUpdate'])->name('vupdate');
    Route::post('/armada/{vehicle}/sembunyikan', [P::class, 'vehicleToggle'])->name('vtoggle');
    Route::get('/kalender', [P::class, 'calendar'])->name('calendar');
    Route::post('/kalender/blokir', [P::class, 'block'])->name('block');
    Route::get('/notifikasi', [P::class, 'notices'])->name('notices');
    Route::post('/notifikasi/{notice}/baca', [P::class, 'read'])->name('read');
    Route::post('/notifikasi/baca-semua', [P::class, 'readAll'])->name('readall');
    Route::get('/pesanan', [P::class, 'incoming'])->name('incoming');
    Route::get('/pembayaran', [P::class, 'payments'])->name('payments');
    Route::post('/pesanan/{booking}/action', [P::class, 'action'])->name('action');
    Route::get('/keuangan', [P::class, 'finance'])->name('finance');
    Route::post('/keuangan/tarik', [P::class, 'payout'])->name('payout');
    Route::get('/ulasan', [P::class, 'reviews'])->name('reviews');
    Route::post('/ulasan/{review}/balas', [P::class, 'reply'])->name('reply');
    Route::get('/klien', [P::class, 'clients'])->name('clients');
    Route::get('/pengaturan', [P::class, 'settings'])->name('settings');
    Route::post('/pengaturan/profil', [P::class, 'saveProfile'])->name('sprofile');
    Route::post('/pengaturan/rekening', [P::class, 'saveBank'])->name('sbank');
});
if (!function_exists('rp')) { function rp($n) { return 'Rp '.number_format($n, 0, ',', '.'); } }
