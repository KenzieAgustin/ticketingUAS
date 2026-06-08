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
        body { background-color: #f8f9fa; margin: 0; }

        /* SIDEBAR */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1a1a2e, #16213e, #0f3460);
            padding-top: 0;
            position: sticky;
            top: 0;
        }

        
        .sidebar .brand {
            background: linear-gradient(90deg, #ff6b6b, #ffd93d, #6bcb77, #4d96ff, #c77dff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.1rem;
            font-weight: 800;
            padding: 20px 16px 16px;
            border-bottom: 2px solid rgba(255,255,255,0.1);
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .sidebar .nav-label {
            color: rgba(255,255,255,0.4);
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
            color: rgba(255,255,255,0.7);
            border-left: 3px solid transparent;
        }

        .sidebar a:hover { color: white; transform: translateX(4px); }

        
        .sidebar a.menu-1:hover, .sidebar a.menu-1.active { background: rgba(255, 107, 107, 0.2); border-left-color: #ff6b6b; color: #ff6b6b; }
        .sidebar a.menu-2:hover, .sidebar a.menu-2.active { background: rgba(255, 217, 61, 0.2); border-left-color: #ffd93d; color: #ffd93d; }
        .sidebar a.menu-3:hover, .sidebar a.menu-3.active { background: rgba(107, 203, 119, 0.2); border-left-color: #6bcb77; color: #6bcb77; }
        .sidebar a.menu-4:hover, .sidebar a.menu-4.active { background: rgba(77, 150, 255, 0.2); border-left-color: #4d96ff; color: #4d96ff; }
        .sidebar a.menu-5:hover, .sidebar a.menu-5.active { background: rgba(255, 154, 86, 0.2); border-left-color: #ff9a56; color: #ff9a56; }
        .sidebar a.menu-6:hover, .sidebar a.menu-6.active { background: rgba(199, 125, 255, 0.2); border-left-color: #c77dff; color: #c77dff; }

        
        .top-header {
            background: linear-gradient(90deg, #ff6b6b, #ffd93d, #6bcb77, #4d96ff, #c77dff);
            padding: 12px 24px;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 1px;
            text-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .main-content { padding: 24px; }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
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
                🎪 PRJ — Pekan Raya Jakarta · Event & Konser Management
            </div>

            <div class="main-content">

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