<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRJ Ticketing Event & Konser</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { background-color: #faf7f0; margin: 0; }

        .sidebar {
            min-height: 100vh;
            background: #fffdf8;
            border-right: 1px solid #ece4d3;
            padding-top: 0;
            position: sticky;
            top: 0;
        }

        .sidebar .brand {
            color: #b8860b;
            font-size: 1.1rem;
            font-weight: 800;
            padding: 20px 16px 16px;
            border-bottom: 2px solid #ece4d3;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .sidebar .nav-label {
            color: #a39a87;
            font-size: 0.7rem;
            text-transform: uppercase;
            padding: 12px 16px 4px;
            letter-spacing: 2px;
        }

        .sidebar a {
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            margin: 3px 10px;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.2s;
            color: #6b5d4f;
            border-left: 3px solid transparent;
        }

        .sidebar a:hover { color: #3d342a; transform: translateX(4px); background: #f5efe2; }

        .sidebar a.menu-1:hover, .sidebar a.menu-1.active { background: rgba(255, 107, 107, 0.12); border-left-color: #e85d5d; color: #c84545; }
        .sidebar a.menu-2:hover, .sidebar a.menu-2.active { background: rgba(218, 165, 32, 0.15); border-left-color: #daa520; color: #b8860b; }
        .sidebar a.menu-3:hover, .sidebar a.menu-3.active { background: rgba(107, 203, 119, 0.15); border-left-color: #5cb868; color: #4a9856; }
        .sidebar a.menu-4:hover, .sidebar a.menu-4.active { background: rgba(77, 150, 255, 0.12); border-left-color: #4d96ff; color: #3a7ad8; }
        .sidebar a.menu-5:hover, .sidebar a.menu-5.active { background: rgba(255, 154, 86, 0.15); border-left-color: #ff9a56; color: #d97e3f; }
        .sidebar a.menu-6:hover, .sidebar a.menu-6.active { background: rgba(199, 125, 255, 0.12); border-left-color: #c77dff; color: #a85fe0; }

        .top-header {
            background: #fffdf8;
            border-bottom: 2px solid #ece4d3;
            padding: 14px 24px;
            color: #5a4a35;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.5px;
        }

        .main-content { padding: 24px; }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .step-guide {
            background: #fffdf8;
            border: 1px solid #ece4d3;
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .step-guide span { font-size: 0.78rem; color: #a39a87; }
        .step-guide .step {
            background: #f5efe2;
            color: #6b5d4f;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .step-guide .step.done { background: #cfe8d2; color: #2f5d36; }
        .step-guide .step.current { background: #b8860b; color: white; }
        .step-guide .arrow { color: #c9bfac; font-size: 0.7rem; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 px-0 sidebar">
            <div class="brand">
                <i class="bi bi-ticket-perforated-fill"></i> PRJ Ticketing
            </div>
            <div class="nav-label">Event & Konser</div>

            <a href="{{ route('web.event-categories.index') }}"
               class="menu-1 {{ request()->routeIs('web.event-categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags-fill"></i>
                <span>1. Kategori Event</span>
            </a>
            <a href="{{ route('web.stages.index') }}"
               class="menu-2 {{ request()->routeIs('web.stages.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt-fill"></i>
                <span>2. Stage / Area</span>
            </a>
            <a href="{{ route('web.events.index') }}"
               class="menu-3 {{ request()->routeIs('web.events.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event-fill"></i>
                <span>3. Events</span>
            </a>
            <a href="{{ route('web.performers.index') }}"
               class="menu-4 {{ request()->routeIs('web.performers.*') ? 'active' : '' }}">
                <i class="bi bi-music-note-beamed"></i>
                <span>4. Performer</span>
            </a>
            <a href="{{ route('web.event-schedules.index') }}"
               class="menu-5 {{ request()->routeIs('web.event-schedules.*') ? 'active' : '' }}">
                <i class="bi bi-clock-fill"></i>
                <span>5. Jadwal Event</span>
            </a>
            <a href="{{ route('web.event-media.index') }}"
               class="menu-6 {{ request()->routeIs('web.event-media.*') ? 'active' : '' }}">
                <i class="bi bi-image-fill"></i>
                <span>6. Media Event</span>
            </a>
        </div>

        <div class="col-md-10 px-0">

            <div class="top-header">
                PRJ — Pekan Raya Jakarta · Event & Konser Management
            </div>

            <div class="main-content">

                <div class="step-guide">
                    <span>Urutan isi data:</span>
                    <span class="step {{ request()->routeIs('web.event-categories.*') ? 'current' : '' }}">1 Kategori</span>
                    <span class="arrow">→</span>
                    <span class="step {{ request()->routeIs('web.stages.*') ? 'current' : '' }}">2 Stage</span>
                    <span class="arrow">→</span>
                    <span class="step {{ request()->routeIs('web.events.*') ? 'current' : '' }}">3 Event</span>
                    <span class="arrow">→</span>
                    <span class="step {{ request()->routeIs('web.performers.*') ? 'current' : '' }}">4 Performer</span>
                    <span class="arrow">→</span>
                    <span class="step {{ request()->routeIs('web.event-schedules.*') ? 'current' : '' }}">5 Jadwal</span>
                    <span class="arrow">→</span>
                    <span class="step {{ request()->routeIs('web.event-media.*') ? 'current' : '' }}">6 Media</span>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Ada kesalahan:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>