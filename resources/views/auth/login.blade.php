<<<<<<< HEAD
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
=======
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'RuteKita — Login' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --rk-green: #046A58;
            --rk-green-dark: #035143;
            --rk-blue: #0F4C81;
            --rk-blue-deep: #0A2E4D;
            --rk-sheet-bg: #F6F9FB;
            --rk-text: #102A43;
            --rk-muted: #7B8794;
            --rk-line: #D9E2EC;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--rk-sheet-bg);
            color: var(--rk-text);
            -webkit-font-smoothing: antialiased;
        }

        .auth-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--rk-sheet-bg);
        }

        /* Banner Atas: Gradien Hijau & Biru RuteKita (Tanpa Awan) */
        .auth-hero {
            position: relative;
            height: 41vh;
            min-height: 250px;
            max-height: 360px;
            width: 100%;
            background: linear-gradient(135deg, var(--rk-blue-deep) 0%, var(--rk-blue) 48%, var(--rk-green) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #FFFFFF;
            padding: 24px 20px 44px;
            overflow: hidden;
        }

        .auth-hero::before {
            content: '';
            position: absolute;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            top: -110px;
            right: -70px;
            pointer-events: none;
        }

        .auth-hero::after {
            content: '';
            position: absolute;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(4, 106, 88, 0.28);
            bottom: -120px;
            left: -60px;
            pointer-events: none;
        }

        .hero-icon-ring {
            position: relative;
            z-index: 1;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 1.5px solid rgba(255, 255, 255, 0.78);
            background: rgba(255, 255, 255, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #FFFFFF;
            margin-bottom: 14px;
        }

        .hero-brand-title {
            position: relative;
            z-index: 1;
            font-size: 23px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .hero-brand-sub {
            position: relative;
            z-index: 1;
            font-size: 13px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.86);
            margin-top: 5px;
        }

        /* Sheet Bawah Melengkung (Menyatu Persis Foto Ketiga) */
        .auth-sheet {
            position: relative;
            z-index: 2;
            flex: 1;
            background-color: var(--rk-sheet-bg);
            border-top-left-radius: 26px;
            border-top-right-radius: 26px;
            margin-top: -22px;
            padding: 28px 6% 42px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .auth-container {
            width: 100%;
            max-width: 1240px;
        }

        /* Tab Login & Signup di Tengah */
        .auth-tabs {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 28px;
            margin-bottom: 34px;
        }

        .auth-tab-btn {
            background: none;
            border: none;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            color: var(--rk-muted);
            padding-bottom: 6px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: color 0.2s, border-color 0.2s;
        }

        .auth-tab-btn.active {
            color: var(--rk-green);
            font-weight: 700;
            border-bottom-color: var(--rk-blue);
        }

        /* Form Garis Bawah Minimalis */
        .input-group {
            margin-bottom: 26px;
        }

        .input-label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #627D98;
            margin-bottom: 8px;
        }

        .input-line {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 1px solid var(--rk-line);
            padding: 8px 2px 10px;
            font-family: inherit;
            font-size: 13.5px;
            color: var(--rk-text);
            outline: none;
            transition: border-color 0.2s;
        }

        .input-line::placeholder {
            color: #9FB3C8;
        }

        .input-line:focus {
            border-bottom-color: var(--rk-green);
        }

        .forgot-wrap {
            margin-top: -12px;
            margin-bottom: 34px;
        }

        .forgot-btn {
            background: none;
            border: none;
            font-family: inherit;
            font-size: 12px;
            font-weight: 500;
            color: #486581;
            cursor: pointer;
        }

        .forgot-btn:hover {
            color: var(--rk-blue);
            text-decoration: underline;
        }

        /* Tombol Submit Lebar Membulat (Tema Hijau-Biru RuteKita) */
        .btn-submit-pill {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--rk-green) 0%, var(--rk-blue) 100%);
            color: #FFFFFF;
            font-family: inherit;
            font-size: 12.5px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(15, 76, 129, 0.2);
            transition: filter 0.2s, transform 0.1s;
        }

        .btn-submit-pill:hover {
            filter: brightness(1.06);
        }

        .btn-submit-pill:active {
            transform: scale(0.995);
        }

        .alert-box {
            padding: 11px 16px;
            border-radius: 12px;
            font-size: 12.5px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }

        .alert-status {
            background: #E2F3EF;
            color: #046A58;
            border: 1px solid #A7DDD0;
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }
    </style>
</head>
<body>
    @php
        $initialTab = $activeTab ?? 'login';
    @endphp

    <div class="auth-shell">
        <!-- Bagian Atas: Banner RuteKita (Hijau & Biru, Tanpa Awan) -->
           <section class="auth-hero">
                <div class="hero-icon-ring" style="overflow: hidden; padding: 0;">
                    <img src="{{ asset('image/image/logologin.jpeg') }}" alt="Logo RuteKita" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block; transform: scale(1.65);">
                </div>
                <h1 class="hero-brand-title">RUTEKITA</h1>

                <p class="hero-brand-sub">Sewa Kendaraan &amp; Mobilitas Harian App</p>
        </section>

        <!-- Bagian Bawah: Sheet Melengkung Menyatu -->
        <section class="auth-sheet">
            <div class="auth-container">
                <!-- Tab Login / Signup -->
                <div class="auth-tabs" role="tablist">
                    <button type="button"
                            id="tab-btn-login"
                            class="auth-tab-btn {{ $initialTab === 'login' ? 'active' : '' }}"
                            onclick="switchAuthTab('login')">
                        Login
                    </button>
                    <button type="button"
                            id="tab-btn-signup"
                            class="auth-tab-btn {{ $initialTab === 'register' ? 'active' : '' }}"
                            onclick="switchAuthTab('register')">
                        Signup
                    </button>
                </div>

                @if ($errors->any())
                    <div class="alert-box alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert-box alert-status">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Panel 1: Login -->
                <div id="panel-login" class="tab-panel {{ $initialTab === 'login' ? 'active' : '' }}">
                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf
                        <div class="input-group">
                            <label for="login_email" class="input-label">E-mail Address</label>
                            <input id="login_email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="input-line"
                                   placeholder="pengguna@rutekita.id"
                                   required
                                   autofocus>
                        </div>

                        <div class="input-group">
                            <label for="login_password" class="input-label">Password</label>
                            <input id="login_password"
                                   type="password"
                                   name="password"
                                   class="input-line"
                                   placeholder="••••••••"
                                   required>
                        </div>

                        <div class="forgot-wrap">
                            <button type="button" class="forgot-btn" onclick="switchAuthTab('forgot')">
                                Forgot password!
                            </button>
                        </div>

                        <button type="submit" class="btn-submit-pill">LOGIN</button>
                    </form>
                </div>

                <!-- Panel 2: Signup -->
                <div id="panel-register" class="tab-panel {{ $initialTab === 'register' ? 'active' : '' }}">
                    <form method="POST" action="{{ route('register.submit') }}">
                        @csrf
                        <div class="input-group">
                            <label for="signup_name" class="input-label">Full Name</label>
                            <input id="signup_name"
                                   type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="input-line"
                                   placeholder="Nama Lengkap"
                                   required>
                        </div>

                        <div class="input-group">
                            <label for="signup_email" class="input-label">E-mail Address</label>
                            <input id="signup_email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="input-line"
                                   placeholder="pengguna@rutekita.id"
                                   required>
                        </div>

                        <div class="input-group">
                            <label for="signup_password" class="input-label">Password</label>
                            <input id="signup_password"
                                   type="password"
                                   name="password"
                                   class="input-line"
                                   placeholder="Minimal 6 karakter"
                                   required>
                        </div>

                        <button type="submit" class="btn-submit-pill" style="margin-top: 14px;">SIGNUP</button>
                    </form>
                </div>

                <!-- Panel 3: Forgot Password -->
                <div id="panel-forgot" class="tab-panel {{ $initialTab === 'forgot' ? 'active' : '' }}">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="input-group">
                            <label for="forgot_email" class="input-label">E-mail Address</label>
                            <input id="forgot_email"
                                   type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="input-line"
                                   placeholder="Masukkan email akun RuteKita Anda"
                                   required>
                        </div>

                        <div class="forgot-wrap">
                            <button type="button" class="forgot-btn" onclick="switchAuthTab('login')">
                                Kembali ke Login
                            </button>
                        </div>

                        <button type="submit" class="btn-submit-pill">RESET PASSWORD</button>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <script>
        function switchAuthTab(tabName) {
            ['login', 'register', 'forgot'].forEach(function (name) {
                const panel = document.getElementById('panel-' + name);
                if (panel) {
                    panel.classList.toggle('active', name === tabName);
                }
            });

            document.getElementById('tab-btn-login').classList.toggle('active', tabName === 'login');
            document.getElementById('tab-btn-signup').classList.toggle('active', tabName === 'register');
        }
    </script>
</body>
</html>
>>>>>>> a2c916b (login)
