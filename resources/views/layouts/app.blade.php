<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TicketingUAS') — Admin Panel</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #0f1117;
            --surface:   #171923;
            --surface-2: #1e2130;
            --border:    #2a2f45;
            --accent:    #6366f1;
            --accent-2:  #818cf8;
            --success:   #22c55e;
            --warning:   #f59e0b;
            --danger:    #ef4444;
            --text:      #e2e8f0;
            --text-muted:#94a3b8;
            --sidebar-w: 240px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform .25s ease;
        }
        .sidebar-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-logo a {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }
        .logo-icon {
            width: 36px; height: 36px; background: var(--accent);
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 16px; font-weight: 700; color: var(--text);
            letter-spacing: -.3px;
        }
        .logo-sub { font-size: 11px; color: var(--text-muted); }

        .sidebar-nav { flex: 1; padding: 12px 0; overflow-y: auto; }
        .nav-section { margin-bottom: 4px; }
        .nav-label {
            font-size: 10px; font-weight: 600; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 1px;
            padding: 12px 20px 6px;
        }
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 20px; color: var(--text-muted);
            text-decoration: none; font-size: 13.5px; font-weight: 500;
            border-radius: 0; transition: all .15s;
            position: relative;
        }
        .nav-link:hover { background: var(--surface-2); color: var(--text); }
        .nav-link.active {
            background: rgba(99,102,241,.12);
            color: var(--accent-2);
        }
        .nav-link.active::before {
            content: ''; position: absolute; left: 0; top: 4px; bottom: 4px;
            width: 3px; background: var(--accent); border-radius: 0 2px 2px 0;
        }
        .nav-icon { width: 18px; text-align: center; font-size: 15px; }

        .sidebar-footer {
            border-top: 1px solid var(--border);
            padding: 12px 20px;
        }
        .user-card {
            display: flex; align-items: center; gap: 10px;
        }
        .avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--accent); display: flex; align-items: center;
            justify-content: center; font-size: 13px; font-weight: 600; color: #fff;
            flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 500; color: var(--text); }
        .user-role { font-size: 11px; color: var(--text-muted); }
        .logout-btn {
            margin-left: auto; background: none; border: none;
            color: var(--text-muted); cursor: pointer; font-size: 16px;
            padding: 4px; border-radius: 4px; transition: color .15s;
        }
        .logout-btn:hover { color: var(--danger); }

        /* ── Main ── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 17px; font-weight: 600; color: var(--text);
        }
        .topbar-actions { display: flex; align-items: center; gap: 8px; }

        .content {
            padding: 28px;
            flex: 1;
        }

        /* ── Cards ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }
        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 15px; font-weight: 600; color: var(--text);
        }
        .card-body { padding: 22px; }

        /* ── Stats grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
        }
        .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: .5px; }
        .stat-value { font-family: 'Space Grotesk', sans-serif; font-size: 28px; font-weight: 700; color: var(--text); margin: 6px 0 4px; }
        .stat-sub { font-size: 12px; color: var(--text-muted); }
        .stat-icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; margin-bottom: 14px;
        }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: var(--surface-2);
            padding: 11px 16px;
            font-size: 11px; font-weight: 600; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: .7px;
            text-align: left; white-space: nowrap;
        }
        tbody td {
            padding: 13px 16px;
            font-size: 13.5px; color: var(--text);
            border-top: 1px solid var(--border);
        }
        tbody tr:hover td { background: var(--surface-2); }
        .td-muted { color: var(--text-muted); font-size: 12.5px; }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11.5px; font-weight: 600;
        }
        .badge-success  { background: rgba(34,197,94,.15);  color: #4ade80; }
        .badge-warning  { background: rgba(245,158,11,.15); color: #fbbf24; }
        .badge-danger   { background: rgba(239,68,68,.15);  color: #f87171; }
        .badge-info     { background: rgba(99,102,241,.15); color: #a5b4fc; }
        .badge-neutral  { background: rgba(148,163,184,.1); color: var(--text-muted); }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 600; font-family: 'Inter', sans-serif;
            cursor: pointer; border: none; text-decoration: none;
            transition: all .15s;
        }
        .btn-primary  { background: var(--accent); color: #fff; }
        .btn-primary:hover  { background: var(--accent-2); color: #fff; }
        .btn-ghost    { background: transparent; border: 1px solid var(--border); color: var(--text-muted); }
        .btn-ghost:hover    { border-color: var(--accent); color: var(--accent-2); }
        .btn-danger   { background: rgba(239,68,68,.15); color: #f87171; border: 1px solid rgba(239,68,68,.2); }
        .btn-danger:hover   { background: rgba(239,68,68,.25); }
        .btn-success  { background: rgba(34,197,94,.15); color: #4ade80; border: 1px solid rgba(34,197,94,.2); }
        .btn-success:hover  { background: rgba(34,197,94,.25); }
        .btn-sm { padding: 5px 12px; font-size: 12px; }

        /* ── Forms ── */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 12.5px; font-weight: 600; color: var(--text-muted); margin-bottom: 7px; text-transform: uppercase; letter-spacing: .4px; }
        .form-control {
            width: 100%; padding: 10px 14px;
            background: var(--surface-2); border: 1px solid var(--border);
            border-radius: 8px; color: var(--text); font-size: 14px;
            font-family: 'Inter', sans-serif; transition: border-color .15s;
            outline: none;
        }
        .form-control:focus { border-color: var(--accent); }
        .form-control::placeholder { color: var(--text-muted); }
        select.form-control { cursor: pointer; }
        .form-control option { background: var(--surface-2); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* ── Alerts ── */
        .alert {
            padding: 12px 16px; border-radius: 8px;
            font-size: 13.5px; margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 10px;
        }
        .alert-success { background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.25); color: #4ade80; }
        .alert-danger  { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.25); color: #f87171; }
        .alert-warning { background: rgba(245,158,11,.1); border: 1px solid rgba(245,158,11,.25); color: #fbbf24; }

        /* ── Modal ── */
        .modal-backdrop {
            position: fixed; inset: 0; background: rgba(0,0,0,.65);
            z-index: 200; display: none; align-items: center; justify-content: center;
        }
        .modal-backdrop.open { display: flex; }
        .modal {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 14px; width: 100%; max-width: 500px;
            max-height: 90vh; overflow-y: auto;
            padding: 24px;
        }
        .modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .modal-title { font-family: 'Space Grotesk', sans-serif; font-size: 17px; font-weight: 700; }
        .modal-close { background: none; border: none; color: var(--text-muted); font-size: 20px; cursor: pointer; }
        .modal-footer { margin-top: 22px; display: flex; justify-content: flex-end; gap: 10px; }

        /* ── Pagination ── */
        .pagination {
            display: flex; align-items: center; gap: 6px;
            padding: 16px 22px;
            border-top: 1px solid var(--border);
        }
        .page-btn {
            padding: 6px 12px; border-radius: 6px;
            font-size: 13px; border: 1px solid var(--border);
            background: transparent; color: var(--text-muted); cursor: pointer;
            text-decoration: none;
        }
        .page-btn:hover, .page-btn.active {
            background: var(--accent); border-color: var(--accent); color: #fff;
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center; padding: 60px 20px; color: var(--text-muted);
        }
        .empty-state-icon { font-size: 40px; margin-bottom: 12px; }
        .empty-state-text { font-size: 14px; }

        /* ── Filter bar ── */
        .filter-bar {
            display: flex; align-items: center; gap: 10px;
            flex-wrap: wrap; margin-bottom: 20px;
        }
        .filter-bar .form-control { width: auto; min-width: 150px; }

        /* ── Stars ── */
        .stars { color: #f59e0b; font-size: 14px; letter-spacing: 1px; }

        /* ── Toggle sidebar on mobile ── */
        .menu-toggle {
            display: none; background: none; border: none;
            color: var(--text); font-size: 20px; cursor: pointer;
        }
        @media(max-width:768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrap { margin-left: 0; }
            .menu-toggle { display: block; }
            .form-row { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2,1fr); }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── Sidebar ── --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="{{ url('/') }}">
            <div class="logo-icon">🎫</div>
            <div>
                <div class="logo-text">TicketingUAS</div>
                <div class="logo-sub">Admin Panel</div>
            </div>
        </a>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-label">Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Dashboard
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Manajemen</div>
            <a href="{{ route('gates.index') }}" class="nav-link {{ request()->routeIs('gates.*') ? 'active' : '' }}">
                <span class="nav-icon">🚪</span> Gate
            </a>
            <a href="{{ route('staff-assignments.index') }}" class="nav-link {{ request()->routeIs('staff-assignments.*') ? 'active' : '' }}">
                <span class="nav-icon">👥</span> Jadwal Staff
            </a>
            <a href="{{ route('check-ins.index') }}" class="nav-link {{ request()->routeIs('check-ins.*') ? 'active' : '' }}">
                <span class="nav-icon">✅</span> Check-in
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-label">Konten</div>
            <a href="{{ route('reviews.index') }}" class="nav-link {{ request()->routeIs('reviews.*') ? 'active' : '' }}">
                <span class="nav-icon">⭐</span> Review
            </a>
            <a href="{{ route('sales-report.index') }}" class="nav-link {{ request()->routeIs('sales-report.*') ? 'active' : '' }}">
                <span class="nav-icon">📈</span> Sales Report
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="user-role">{{ auth()->user()->role ?? 'admin' }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin-left:auto">
                @csrf
                <button type="submit" class="logout-btn" title="Logout">⏻</button>
            </form>
        </div>
    </div>
</aside>

{{-- ── Main ── --}}
<div class="main-wrap">
    <header class="topbar">
        <div style="display:flex;align-items:center;gap:12px">
            <button class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
        </div>
        <div class="topbar-actions">
            @yield('topbar-actions')
        </div>
    </header>

    <main class="content">
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">✕ {{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>

<script>
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth <= 768 && sidebar.classList.contains('open') && !sidebar.contains(e.target) && !e.target.closest('.menu-toggle')) {
            sidebar.classList.remove('open');
        }
    });
</script>

@stack('scripts')
</body>
</html>