<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title') - RuteKita Partner</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<style>body{font-family:'Plus Jakarta Sans',sans-serif}.g{background:#064e3b}.btn{background:#047857;color:#fff;border-radius:8px;padding:6px 12px;font-size:12px;font-weight:600}.card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:20px}th{font-size:10px;text-transform:uppercase;color:#64748b;text-align:left;padding:10px 12px;background:#f8fafc}td{padding:12px;font-size:12px;border-top:1px solid #f1f5f9}input,select,textarea{background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;font-size:12px;width:100%}</style></head>
<body class="bg-slate-50">
@php $menu=[['p.dashboard','fa-house','Dashboard Utama'],['p.fleet','fa-car','Tambah & Kelola Armada'],['p.calendar','fa-calendar','Jadwal Booking (Kalender)'],['p.notices','fa-bell','Pesanan Masuk'],['p.payments','fa-file-lines','Kelola Pesanan & List Payment'],['p.finance','fa-wallet','Laporan Keuangan & Payout'],['p.reviews','fa-star','Ulasan & Rating'],['p.clients','fa-address-book','Riwayat Klien'],['p.settings','fa-store','Pengaturan Showroom']]; $sr=auth()->user()->showroom; @endphp
<header class="g text-white px-10 pt-5 pb-16">
  <div class="flex justify-between items-center"><div class="font-extrabold text-lg">Rute<span class="text-amber-400">Kita.</span> <small class="text-[9px] font-normal">Partner</small></div>
  <div class="flex items-center gap-4"><a href="{{ route('p.notices') }}" class="relative text-amber-400"><i class="fa-solid fa-bell text-lg"></i>@if($sr->notices()->where('read',false)->count())<span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>@endif</a><span class="border border-amber-400 text-amber-400 rounded-full px-3 py-1 text-[11px] font-semibold"><i class="fa-solid fa-store"></i> {{ $sr->name }}</span></div></div>
  <div class="flex justify-between items-end mt-6"><div><h1 class="text-xl font-bold">@yield('h1')</h1><p class="text-[11px] opacity-80 mt-1">@yield('sub')</p></div><div class="flex gap-2">@yield('actions')</div></div>
</header>
<div class="flex -mt-8 bg-slate-50 rounded-tl-[40px] min-h-[70vh]">
<aside class="w-60 bg-white p-3 rounded-tl-[40px] flex flex-col justify-between" style="min-height:70vh"><nav class="space-y-1 mt-4">
@foreach($menu as [$rt,$ic,$lb])<a href="{{ route($rt) }}" class="flex gap-3 items-center px-3 py-2 rounded-lg text-[11px] font-semibold {{ request()->routeIs($rt) ? 'bg-emerald-50 text-emerald-800' : 'text-slate-500' }}"><i class="fa-solid {{ $ic }} w-4"></i>{{ $lb }}</a>@endforeach</nav>
<div class="text-[11px] font-semibold space-y-2 px-3 pb-4"><div class="text-slate-500"><i class="fa-regular fa-circle-question"></i> Help</div><form method="post" action="{{ route('logout') }}">@csrf<button class="text-red-600"><i class="fa-solid fa-right-from-bracket"></i> Keluar Beranda</button></form></div></aside>
<main class="flex-1 p-6 space-y-5">
@if(session('ok'))<div class="bg-emerald-50 text-emerald-800 text-xs rounded-lg p-3">{{ session('ok') }}</div>@endif
@if($errors->any())<div class="bg-red-50 text-red-700 text-xs rounded-lg p-3">{{ $errors->first() }}</div>@endif
@yield('content')</main></div></body></html>
