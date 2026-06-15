<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRJ Ticketing Event & Konser</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * { box-sizing: border-box; }
        
        body { 
            margin: 0; 
            color: #333;
            overflow-x: hidden;
            background-color: #0f172a; /* Warna dasar gelap */
        }

        /* --- 1. SIDEBAR DENGAN EFEK BINTANG CSS (Pasti Muncul) --- */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #16162a 0%, #0f3460 100%);
            position: sticky;
            top: 0;
            border-right: 1px solid rgba(246, 200, 95, 0.2);
            box-shadow: 5px 0 20px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        /* Bikin rasi bintang manual pake CSS biar nggak usah load gambar internet */
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: 
                radial-gradient(white, rgba(255,255,255,.2) 2px, transparent 4px),
                radial-gradient(white, rgba(255,255,255,.15) 1px, transparent 30px),
                radial-gradient(white, rgba(255,255,255,.1) 2px, transparent 40px);
            background-size: 550px 550px, 350px 350px, 250px 250px;
            background-position: 0 0, 40px 60px, 130px 270px;
            opacity: 0.6;
            z-index: 0;
        }

        .sidebar > * { position: relative; z-index: 1; } /* Teks sidebar di atas bintang */

        .sidebar .brand {
            background: linear-gradient(90deg, #F6C85F, #f9df9f);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.1rem;
            font-weight: 800;
            padding: 20px 16px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 8px;
        }

        .sidebar .nav-label {
            color: #b0a8ba; font-size: 0.7rem; text-transform: uppercase;
            padding: 12px 16px 4px; letter-spacing: 2px;
        }

        .sidebar a {
            text-decoration: none; display: flex; align-items: center; gap: 10px;
            padding: 10px 16px; margin: 3px 10px; border-radius: 8px;
            font-size: 0.88rem; font-weight: 500; transition: all 0.3s ease;
            color: rgba(255,255,255,0.7); border-left: 3px solid transparent;
        }

        .sidebar a:hover, .sidebar a.active { 
            color: white; transform: translateX(4px); 
            background: rgba(107, 76, 122, 0.5); border-left-color: #F6C85F;
        }

        /* --- 2. HEADER ATAS --- */
        .top-header {
            background: rgba(22, 22, 42, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(246, 200, 95, 0.2);
            padding: 12px 24px;
            color: #F6C85F; font-weight: 700; font-size: 1rem;
            position: relative; z-index: 2;
        }

        /* --- 3. BACKGROUND KONTEN UTAMA (PAKAI GAMBAR LOKAL) --- */
        .content-wrapper {
            /* INI YANG BIKIN GAMBAR MUNCUL: Panggil gambar dari folder public/images/ */
            background-image: url('{{ asset("images/bg-prj.jpg") }}'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            position: relative;
        }

        /* Bikin filter tipis biar tabelnya lebih 'pop-out' */
        .content-wrapper::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255, 255, 255, 0.15); /* Sedikit dicerahkan */
            backdrop-filter: blur(1px);
            z-index: 0;
        }

        .main-content {
            padding: 30px;
            position: relative;
            z-index: 1; /* Pastikan konten di atas background */
        }

        .page-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 24px; color: #2D2466;
            text-shadow: 0 2px 4px rgba(255,255,255,0.8); /* Biar teks judul kebaca */
        }
        
        .page-header h2 { font-size: 1.5rem; font-weight: 700; }

        .btn-add {
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(45, 36, 102, 0.2);
            color: #2D2466; padding: 8px 16px; border-radius: 8px;
            font-weight: 600; transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-add:hover { background-color: white; transform: translateY(-2px); }

        /* --- 4. TABEL KACA (GLASSMORPHISM) --- */
        .table-container {
            /* Background tabel dibuat sangat transparan biar gambar belakang keliatan */
            background-color: rgba(255, 255, 255, 0.45); 
            backdrop-filter: blur(8px); /* Efek blur kaca */
            -webkit-backdrop-filter: blur(8px);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.6);
            overflow: hidden;
        }

        table { width: 100%; border-collapse: collapse; }

        thead {
            background-color: rgba(45, 36, 102, 0.85); /* Ungu transparan */
        }

        th {
            color: #F6C85F; text-align: left;
            padding: 15px 20px; font-weight: 600;
        }

        tbody tr {
            border-bottom: 1px solid rgba(45, 36, 102, 0.1);
            transition: background 0.3s ease;
        }
        
        tbody tr:hover { background-color: rgba(255, 255, 255, 0.3); }
        tbody tr:last-child { border-bottom: none; }
        td { padding: 15px 20px; font-weight: 500; color: #1a1a1a; }

        .badge {
            background-color: rgba(107, 76, 122, 0.3);
            color: #2D2466; padding: 6px 12px; border-radius: 20px;
            font-size: 0.85rem; font-weight: 600;
            border: 1px solid rgba(255,255,255,0.5);
        }

        .btn-action {
            background: rgba(255,255,255,0.5); border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 5px 10px; border-radius: 5px; color: #333;
            cursor: pointer; transition: all 0.3s ease;
        }
        .btn-action:hover { background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="container-fluid px-0">
    <div class="row gx-0">
        
        <div class="col-md-2 sidebar px-0">
            <div class="brand">
                <i class="bi bi-star-fill"></i> PRJ Ticketing
                <p class="brand-subtitle mb-0 text-white-50" style="font-size: 0.75rem;">Jakarta Fair 2026</p>
            </div>
            <div class="nav-label">Event & Konser</div>

            <a href="{{ route('web.event-categories.index') }}"
               class="menu-1 {{ request()->routeIs('web.event-categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags-fill"></i>
                <span>1. Kategori Event</span>
            </a>
            <a href="#"><i class="bi bi-geo-alt-fill"></i><span>2. Stage / Area</span></a>
            <a href="#"><i class="bi bi-calendar-event-fill"></i><span>3. Events</span></a>
            <a href="#"><i class="bi bi-music-note-beamed"></i><span>4. Performer</span></a>
            <a href="#"><i class="bi bi-clock-fill"></i><span>5. Jadwal Event</span></a>
            <a href="#"><i class="bi bi-image-fill"></i><span>6. Media Event</span></a>
        </div>

        <div class="col-md-10 content-wrapper px-0">

            <div class="top-header">
                ✦ PRJ - Pekan Raya Jakarta • Event & Konser Management ✦
            </div>

            <div class="main-content">

                <div class="page-header">
                    <h2><i class="bi bi-tag-fill me-2"></i>Kategori Event</h2>
                    <button class="btn-add"><i class="bi bi-plus me-1"></i>Tambah Kategori</button>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Slug</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Konser</td>
                                <td><span class="badge">konser</span></td>
                                <td>-</td>
                                <td>
                                    <button class="btn-action"><i class="bi bi-eye"></i></button>
                                    <button class="btn-action"><i class="bi bi-pencil"></i></button>
                                    <button class="btn-action"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Pameran</td>
                                <td><span class="badge">pameran</span></td>
                                <td>Pameran produk & UMKM</td>
                                <td>
                                    <button class="btn-action"><i class="bi bi-eye"></i></button>
                                    <button class="btn-action"><i class="bi bi-pencil"></i></button>
                                    <button class="btn-action"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                {{-- @yield('content') --}}
                
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>