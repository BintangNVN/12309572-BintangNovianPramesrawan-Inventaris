<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Inventaris</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    @vite(['resources/css/app.css'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f5f9;
            color: #0f172a;
            min-height: 100vh;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* ─── SIDEBAR ─── */
        aside {
            width: 260px;
            min-height: 100vh;
            background: #1e3a8a;
            display: flex;
            flex-direction: column;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }

        .sidebar-brand img {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            object-fit: contain;
            padding: 4px;
        }

        .sidebar-brand-logo {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fbbf24;
        }

        .sidebar-brand-text {
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand-text .label {
            font-size: 10px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.55);
        }

        .sidebar-brand-text .title {
            font-size: 17px;
            font-weight: 700;
            margin-top: 2px;
            letter-spacing: 0.02em;
        }

        nav {
            flex: 1;
            padding: 20px 16px;
        }

        .sidebar-footer {
            padding: 20px 16px;
            border-top: 1px solid rgba(255,255,255,0.12);
        }

        .sidebar-footer-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: rgba(255,255,255,0.55);
            margin-bottom: 8px;
        }

        .sidebar-footer-user {
            background: rgba(255,255,255,0.12);
            border-radius: 12px;
            padding: 12px 16px;
        }

        .sidebar-footer-name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
        }

        .sidebar-footer-role {
            font-size: 11px;
            color: rgba(255,255,255,0.7);
            margin-top: 2px;
        }

        .nav-section-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: rgba(255,255,255,0.45);
            padding: 18px 10px 8px;
            font-weight: 600;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 10px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.15s, color 0.15s;
            margin-bottom: 2px;
            cursor: pointer;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }

        .nav-item.dropdown {
            position: relative;
        }

        .nav-item.dropdown summary {
            list-style: none;
            margin: 0;
        }

        .nav-item.dropdown summary::-webkit-details-marker,
        .nav-item.dropdown summary::marker {
            display: none;
        }

        .nav-dropdown-menu {
            display: none;
            margin-top: 8px;
            padding-left: 18px;
        }

        .nav-item.dropdown[open] .nav-dropdown-menu {
            display: block;
        }

        .dropdown-icon {
            margin-left: auto;
            transition: transform 0.15s ease;
        }

        .nav-item.dropdown[open] .dropdown-icon {
            transform: rotate(180deg);
        }

        .nav-dropdown-menu li {
            margin-bottom: 8px;
        }

        .badge-circle {
            width: 6px;
            height: 6px;
            display: inline-block;
            border-radius: 50%;
            background: #fff;
        }

        .nav-link.active {
            background: rgba(255,255,255,0.18);
            color: #fff;
            font-weight: 600;
        }

        .nav-link i {
            width: 18px;
            font-size: 15px;
            flex-shrink: 0;
        }

        .nav-link .arrow {
            margin-left: auto;
            font-size: 11px;
            color: rgba(255,255,255,0.5);
        }

        /* ─── MAIN CONTENT ─── */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ─── HEADER ─── */
        header {
            position: relative;
            overflow: hidden;
            height: 225px;
        }

        .header-bg {
            position: absolute;
            inset: 0;
            background: url('{{ asset('images/bg-gunung.jpg') }}') center/cover no-repeat;
        }

        .header-top {
            position: relative;
            z-index: 10;
            padding: 18px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        #sidebarToggle {
            position: absolute;
            left: -9999px;
        }

        .hamburger-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 22px;
            cursor: pointer;
            display: none;
        }

        .header-logo-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .header-logo-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .header-logo-circle img {
            width: 38px;
            height: 38px;
            object-fit: contain;
        }

        .header-logo-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.15); /* FIX */
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255,255,255,0.3);
            color: #fbbf24;
            font-size: 22px;
        }

        .header-title {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.01em;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-date {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.01em;
        }

        /* Profile button */
        .profile-wrap {
            position: relative;
        }

        .profile-wrap summary {
            list-style: none;
            margin: 0;
        }

        .profile-wrap summary::-webkit-details-marker,
        .profile-wrap summary::marker {
            display: none;
        }

        .profile-wrap[open] .profile-dropdown {
            display: block;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            border: none;
            border-radius: 999px;
            padding: 6px 14px 6px 6px;
            cursor: pointer;
            color: #0f172a;
            font-size: 14px;
            font-weight: 600;
        }

        .profile-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 16px;
        }

        .profile-btn .chevron {
            font-size: 11px;
            color: #64748b;
            margin-left: 4px;
        }

        .profile-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            min-width: 160px;
            overflow: hidden;
            z-index: 100;
        }

        .profile-dropdown.open {
            display: block;
        }

        .profile-dropdown a,
        .profile-dropdown button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 13px 18px;
            background: none;
            border: none;
            text-align: left;
            font-size: 14px;
            color: #374151;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.12s;
        }

        .profile-dropdown a:hover,
        .profile-dropdown button:hover {
            background: white;
        }

        .profile-dropdown i {
            color: #3b82f6;
        }

        /* Header sub-bar (check menu in sidebar) */
        .header-subbar {
            position: relative;
            z-index: 10;
            padding: 0 32px 24px;
        }

        .subbar-card {
            background: rgba(255,255,255,0.97);
            border-radius: 16px;
            padding: 14px 24px;
            font-size: 14px;
            color: #475569;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }

        /* ─── PAGE CONTENT ─── */
        .page-content {
            padding: 32px;
            flex: 1;
        }

        /* Stats cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: #fff;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .stat-icon.blue { background: #eff6ff; color: #3b82f6; }
        .stat-icon.green { background: #f0fdf4; color: #22c55e; }
        .stat-icon.orange { background: #fff7ed; color: #f97316; }
        .stat-icon.purple { background: #faf5ff; color: #a855f7; }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
        }

        .stat-label {
            font-size: 13px;
            color: #64748b;
        }

        @media (max-width: 768px) {
            aside { display: none; }
            .hamburger-btn { display: block; }
            #sidebarToggle:checked + .layout aside { display: flex; position: fixed; z-index: 200; height: 100vh; top: 0; left: 0; }
            .page-content { padding: 20px; }
            .header-top { padding: 14px 20px; }
            .header-subbar { padding: 0 20px 18px; }
        }
    </style>
</head>
<body>
<input type="checkbox" id="sidebarToggle" hidden>
<div class="layout">

    <!-- SIDEBAR -->
    <aside id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-logo">
                <img src="{{ asset('images/wikrama-logo.png') }}" alt="logo">
            </div>
            <div class="sidebar-brand-text">
                <span class="label">Menu</span>
                <span class="title">Inventaris</span>
            </div>
        </div>

        <nav>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-bar"></i>
                <span>Dashboard</span>
            </a>

            <div class="nav-section-label">Items Data</div>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('categories.index') }}" class="nav-link">
                    <i class="fa-solid fa-list-ul"></i>
                    <span>Categories</span>
                </a>
                <a href="{{ route('items.index')}}" class="nav-link">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Items</span>
                </a>
                <div class="nav-section-label text-white-50 small mb-2">Accounts</div>
                <details class="nav-item dropdown">
                    <summary class="nav-link d-flex align-items-center justify-content-between py-2 px-3 text-white">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-user"></i>
                            <span>Users</span>
                        </div>
                        <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                    </summary>

                    <ul class="nav-dropdown-menu text-white-75 list-unstyled mb-0">
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge-circle"></span>
                            <a href="{{route('admins.index')}}" class="text-white text-decoration-none">Admin</a>
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-0">
                            <span class="badge-circle"></span>
                            <a href="{{route('operators.index')}}" class="text-white text-decoration-none">Operator</a>
                        </li>
                    </ul>
                </details>
            @else
                <a href="{{ route('items_lendings.index')}}" class="nav-link">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Items</span>
                </a>
                <a href="{{route('lendings.index')}}"" class="nav-link">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                    <span>Lending</span>
                </a>
                <div class="nav-section-label text-white-50 small mb-2">Accounts</div>
                <details class="nav-item dropdown">
                    <summary class="nav-link d-flex align-items-center justify-content-between py-2 px-3 text-white">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-user"></i>
                            <span>Users</span>
                        </div>
                        <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                    </summary>

                    <ul class="nav-dropdown-menu text-white-75 list-unstyled mb-0">
                        <li class="d-flex align-items-center gap-2 mb-0">
                            <span class="badge-circle"></span>
                            <a href="{{route('operators.edit', auth()->user()->id)}}" class="text-white text-decoration-none">Edit</a>
                        </li>
                    </ul>
                </details>
            @endif
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-footer-label">Signed in as</div>
            <div class="sidebar-footer-user">
                <div class="sidebar-footer-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-footer-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main">
        <!-- HEADER -->
        <header>
            <div class="header-bg" style="background-image: url({{ asset('images/bg-gunung.jpg') }})"></div>
            <div class="header-overlay"></div>

            <div class="header-top">
                <div class="header-left">
                    <label for="sidebarToggle" class="hamburger-btn" role="button" aria-label="Toggle sidebar">
                        <i class="fa-solid fa-bars"></i>
                    </label>
                    <div class="header-logo-wrap">
                       <div class="header-logo-circle">
                            <img src="{{ asset('images/wikrama-logo.png') }}" alt="logo">
                        </div>
                        <span class="header-title">@yield('pageTitle', 'Welcome Back,' . auth()->user()->name)</span>
                    </div>
                </div>
                <div class="header-right">
                    <span class="header-date">{{ date('j F, Y') }}</span>


                </div>
            </div>

           <div class="header-subbar">
            <div class="subbar-card" style="display:flex; justify-content:space-between; align-items:center;">

        <span>Check menu in sidebar</span>

<details class="profile-wrap">
            <summary class="profile-btn">
                <div class="profile-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <span>{{ auth()->user()->name }}</span>
                <i class="fa-solid fa-chevron-down chevron"></i>
            </summary>

            <div class="profile-dropdown">
                <form method="POST" action="/logout">
                    @csrf
                     <button type="submit">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </button>
                </form>
            </div>
        </details>

    </div>
</div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="page-content">
            @yield('content')
        </main>
    </div>
</div>
<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
