<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-body: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            display: flex;
            /* Sidebar dan Konten bersebelahan */
            min-height: 100vh;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background-color: #0f172a;
            /* Slate 900 gelap premium */
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            padding: 24px;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.08);
            /* Bayangan lembut memisahkan sidebar dan konten putih */
            z-index: 10;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
            /* Scroll internal jika memanjang */
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Hilangkan scrollbar jelek di sidebar */
        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            color: #94a3b8;
            /* Slate 400 */
            text-decoration: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            gap: 12px;
        }

        /* Efek geser kanan memukau saat di hover */
        .menu-link:hover {
            background-color: #1e293b;
            /* Slate 800 */
            color: #ffffff;
            transform: translateX(6px);
        }

        .menu-link.active {
            background: linear-gradient(135deg, var(--primary), #6366f1);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
            font-weight: 600;
        }

        /* --- MAIN CONTENT --- */
        .main-content {
            margin-left: var(--sidebar-width);
            /* Geser konten agar tidak tertimpa sidebar */
            flex: 1;
            padding: 40px;
        }

        /* Gaya Tombol & Tabel tetap sama atau sedikit disesuaikan */
        .btn {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border: none;
            cursor: pointer;
        }

        .card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* (Lanjutkan dengan CSS input/tabel Anda yang sebelumnya agar tetap berfungsi) */
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-family: inherit;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 15px;
            border-bottom: 1px solid var(--border);
            font-size: 12px;
            color: var(--text-muted);
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--border);
        }
    </style>
</head>

<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <!-- Logo Brand -->
        <h2
            style="color: white; text-align: left; margin: 5px 0 30px 10px; font-weight: 800; letter-spacing: 1px; font-size: 24px;">
            <span style="color: #6366f1;">POS</span><span style="opacity: 0.9;">Enterprise</span>
        </h2>

        <!-- Info Identitas (Card Gelap Melayang) -->
        <div
            style="margin-bottom: 35px; background: linear-gradient(145deg, #1e293b, #0f172a); padding: 18px; border-radius: 16px; border: 1px solid #334155; box-shadow: 0 6px 12px rgba(0,0,0,0.1);">
            <div style="font-size: 12px; color: #94a3b8; margin-bottom: 6px;">User:</div>
            <div
                style="color: white; font-size: 17px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; letter-spacing: -0.5px;">
                {{ auth()->user()->name }}
            </div>

            <!-- Badge Role Dinamis: Hijau untuk Admin, Kuning untuk Kasir -->
            <div
                style="display: inline-block; background: {{ auth()->user()->role === 'admin' ? 'rgba(16, 185, 129, 0.2)' : 'rgba(245, 158, 11, 0.2)' }}; color: {{ auth()->user()->role === 'admin' ? '#10b981' : '#f59e0b' }}; padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: 800; margin-top: 12px; text-transform: uppercase; letter-spacing: 1px; border: 1px solid {{ auth()->user()->role === 'admin' ? 'rgba(16, 185, 129, 0.3)' : 'rgba(245, 158, 11, 0.3)' }};">
                {{ auth()->user()->role }}
            </div>
        </div>

        <div class="sidebar-menu">
            <div
                style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; margin: 0 0 12px 10px;">
                Menu Utama</div>

            @if(auth()->user()->role === 'admin')
                <a href="/" class="menu-link {{ Request::is('/') ? 'active' : '' }}">📊 Dashboard</a>
                <a href="/produk" class="menu-link {{ Request::is('produk*') ? 'active' : '' }}">📦 Produk</a>
                <a href="/kategori" class="menu-link {{ Request::is('kategori*') ? 'active' : '' }}">📁 Kategori</a>
                <a href="/inventori" class="menu-link">📦 Inventori Gudang</a>
                <a href="/buku-kas" class="menu-link {{ Request::is('buku-kas*') ? 'active' : '' }}">💰 Buku Kas</a>

            @endif

            <a href="/transaksi/baru" class="menu-link {{ Request::is('transaksi/baru') ? 'active' : '' }}">🛒 Halaman
                Kasir</a>
            <a href="/transaksi"
                class="menu-link {{ Request::is('transaksi') && !Request::is('transaksi/baru') ? 'active' : '' }}">📜
                Riwayat Transaksi</a>
        </div>

        <!-- Tombol Keluar (Logout) Kelas Atas (Menggunakan Form POST POST) -->
        <form action="/logout" method="POST"
            style="margin-top: auto; padding-top: 25px; border-top: 1px solid #1e293b;">
            @csrf
            <button type="submit"
                style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px; background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; padding: 15px; font-size: 15px; cursor: pointer; border-radius: 12px; font-weight: 600; transition: all 0.3s;"
                onmouseover="this.style.backgroundColor='#ef4444'; this.style.color='white'; this.style.borderColor='#ef4444'; this.style.transform='translateY(-2px)';"
                onmouseout="this.style.backgroundColor='rgba(239, 68, 68, 0.05)'; this.style.color='#ef4444'; this.style.borderColor='rgba(239, 68, 68, 0.2)'; this.style.transform='none';">
                Log Out 🔒
            </button>
        </form>
    </div>



    <!-- Area Konten Utama -->
    <div class="main-content">
        @if(session('success'))
            <div style="background-color: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background-color: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            @yield('content')
        </div>
    </div>

</body>

</html>