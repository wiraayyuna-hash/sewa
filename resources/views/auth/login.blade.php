<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Masuk - RuteKita Partner</title>
<script src="https://cdn.tailwindcss.com"></script><link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>body{font-family:'Plus Jakarta Sans',sans-serif}input{background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:10px 12px;font-size:13px;width:100%}</style></head>
<body class="bg-[#064e3b] min-h-screen flex items-center justify-center p-4"><div class="w-full max-w-sm">
<div class="text-center text-white mb-5"><div class="font-extrabold text-3xl">Rute<span class="text-amber-400">Kita.</span> <small class="text-xs font-normal">Partner</small></div><p class="text-xs opacity-80 mt-1">Dasbor Bisnis Rental Kendaraan</p></div>
<form method="post" class="bg-white rounded-2xl p-6 space-y-3 shadow-xl">@csrf
@if($errors->any())<div class="bg-red-50 text-red-700 text-xs rounded-lg p-2">{{ $errors->first() }}</div>@endif
<input name="email" type="email" placeholder="Email" value="{{ old('email') }}" required><input name="password" type="password" placeholder="Password" required>
<button class="w-full bg-emerald-700 text-white rounded-lg py-2.5 text-sm font-bold">Masuk</button>
<div class="text-[11px] text-center text-slate-500">Belum punya akun? <a href="/daftar" class="text-emerald-700 font-bold">Daftar</a> &nbsp;•&nbsp; <a href="/katalog" class="text-emerald-700 font-bold">Lihat Katalog</a></div></form>
<div class="grid grid-cols-2 gap-3 mt-4">
<form method="post">@csrf<input type="hidden" name="email" value="partner@rutekita.test"><input type="hidden" name="password" value="password"><button class="w-full bg-amber-500 text-white rounded-lg py-2 text-xs font-bold">Demo: Partner</button></form>
<form method="post">@csrf<input type="hidden" name="email" value="user@rutekita.test"><input type="hidden" name="password" value="password"><button class="w-full bg-white/90 text-emerald-900 rounded-lg py-2 text-xs font-bold">Demo: Pelanggan</button></form></div></div></body></html>