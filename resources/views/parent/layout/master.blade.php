<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Portal - {{ config('app.name', 'School Management') }}</title>
    <!-- Include your existing CSS/JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            min-height: 100vh;
            background: #fff;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
            width: 280px;
        }

        .nav-link {
            color: #555;
            font-weight: 500;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.05);
            border-radius: 6px;
        }

        .navbar-brand {
            font-weight: 700;
            color: #0d6efd !important;
        }

        .no-arrow::after {
            display: none !important;
        }

        @media (max-width: 991.98px) {
            .sidebar-desktop {
                display: none !important;
            }

            .sidebar-mobile {
                display: block;
            }
        }

        @media (min-width: 992px) {
            .sidebar-mobile {
                display: none !important;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    @php
        $user = Auth::user();
        $guardian = \App\Models\Guardian::where('user_id', $user->id)->first();
        $children = collect();
        $activeChild = null;
        if ($guardian) {
            $children = \App\Models\Student::where('guardian_id', $guardian->id)->get();
            $activeChildId = session('active_child_id');
            if ($activeChildId) {
                $activeChild = $children->firstWhere('id', $activeChildId);
            }
        }
    @endphp

    <div class="d-flex">
        <!-- Desktop Sidebar -->
        <div class="sidebar p-3 sidebar-desktop flex-shrink-0">
            <a href="{{ route('parent.dashboard') }}"
                class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
                <span class="fs-4 navbar-brand"><i class="bi bi-mortarboard-fill me-2"></i>Parent Portal</span>
            </a>
            <hr>

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-1">
                    <a href="{{ route('parent.dashboard') }}"
                        class="nav-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('parent.profile') }}"
                        class="nav-link {{ request()->routeIs('parent.profile') ? 'active' : '' }}">
                        <i class="bi bi-person me-2"></i> Child Profile
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('parent.attendance') }}"
                        class="nav-link {{ request()->routeIs('parent.attendance') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check me-2"></i> Attendance
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('parent.homework') }}"
                        class="nav-link {{ request()->routeIs('parent.homework') ? 'active' : '' }}">
                        <i class="bi bi-journal-text me-2"></i> Homework
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('parent.results') }}"
                        class="nav-link {{ request()->routeIs('parent.results') ? 'active' : '' }}">
                        <i class="bi bi-award me-2"></i> Results
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('parent.fees') }}"
                        class="nav-link {{ request()->routeIs('parent.fees') ? 'active' : '' }}">
                        <i class="bi bi-cash-stack me-2"></i> Fee Invoices
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('parent.notices') }}"
                        class="nav-link {{ request()->routeIs('parent.notices') ? 'active' : '' }}">
                        <i class="bi bi-bell me-2"></i> Notices
                    </a>
                </li>
            </ul>
        </div>

        <!-- Mobile Sidebar (Offcanvas) -->
        <div class="offcanvas offcanvas-start sidebar-mobile" tabindex="-1" id="sidebarParentMobile"
            aria-labelledby="sidebarParentMobileLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title navbar-brand" id="sidebarParentMobileLabel"><i
                        class="bi bi-mortarboard-fill me-2"></i>Parent Portal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-3">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item mb-1">
                        <a href="{{ route('parent.dashboard') }}"
                            class="nav-link {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house-door me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('parent.profile') }}"
                            class="nav-link {{ request()->routeIs('parent.profile') ? 'active' : '' }}">
                            <i class="bi bi-person me-2"></i> Child Profile
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('parent.attendance') }}"
                            class="nav-link {{ request()->routeIs('parent.attendance') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check me-2"></i> Attendance
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('parent.homework') }}"
                            class="nav-link {{ request()->routeIs('parent.homework') ? 'active' : '' }}">
                            <i class="bi bi-journal-text me-2"></i> Homework
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('parent.results') }}"
                            class="nav-link {{ request()->routeIs('parent.results') ? 'active' : '' }}">
                            <i class="bi bi-award me-2"></i> Results
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('parent.fees') }}"
                            class="nav-link {{ request()->routeIs('parent.fees') ? 'active' : '' }}">
                            <i class="bi bi-cash-stack me-2"></i> Fee Invoices
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="{{ route('parent.notices') }}"
                            class="nav-link {{ request()->routeIs('parent.notices') ? 'active' : '' }}">
                            <i class="bi bi-bell me-2"></i> Notices
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1" style="height: 100vh; overflow-y: auto; overflow-x: hidden;">
            <!-- Topbar -->
            <nav
                class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3 px-3 px-md-4 shadow-sm sticky-top">
                <div class="container-fluid px-0">
                    <button class="btn btn-light d-lg-none me-3 border" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#sidebarParentMobile" aria-controls="sidebarParentMobile">
                        <i class="bi bi-list fs-5"></i>
                    </button>

                    <!-- Child Selector -->
                    @if($children->isNotEmpty())
                        <form action="{{ route('parent.set-child') }}" method="POST" class="d-flex align-items-center m-0">
                            @csrf
                            <label
                                class="me-2 fw-semibold text-muted small text-uppercase d-none d-sm-inline">Viewing:</label>
                            <select name="child_id"
                                class="form-select form-select-sm shadow-none bg-light border-0 fw-bold text-primary"
                                style="width: auto; cursor: pointer;" onchange="this.form.submit()">
                                @foreach($children as $child)
                                    <option value="{{ $child->id }}" {{ $activeChild && $activeChild->id == $child->id ? 'selected' : '' }}>
                                        {{ $child->first_name }} {{ $child->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @endif

                    <div class="ms-auto d-flex align-items-center">
                        <!-- Notification Bell -->
                        <div class="dropdown me-3">
                            <a href="#"
                                class="position-relative text-dark fs-5 text-decoration-none dropdown-toggle no-arrow"
                                id="dropdownNotifications" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell-fill"></i>
                                @php
                                    $unreadNoticesCount = \App\Models\Notice::forGuardian($guardian ?? null)
                                        ->where('created_at', '>=', now()->subDays(7))
                                        ->count();
                                @endphp
                                @if($unreadNoticesCount > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        style="font-size: 0.6rem; padding: 0.25em 0.5em;">
                                        {{ $unreadNoticesCount }}
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-4 p-2"
                                aria-labelledby="dropdownNotifications"
                                style="width: 280px; max-height: 350px; overflow-y: auto;">
                                <li class="px-2 py-1 fw-bold border-bottom mb-2 small text-muted">Recent Notices</li>
                                @php
                                    $recentNotices = \App\Models\Notice::forGuardian($guardian ?? null)
                                        ->latest()
                                        ->take(5)
                                        ->get();
                                @endphp
                                @forelse($recentNotices as $n)
                                    <li>
                                        <a class="dropdown-item rounded-3 p-2 text-wrap small"
                                            href="{{ route('parent.notices') }}">
                                            <div class="fw-semibold text-truncate">{{ $n->title }}</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">
                                                {{ $n->created_at->diffForHumans() }}
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li class="text-center py-3 text-muted small">No new notices</li>
                                @endforelse
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-center small fw-semibold text-primary"
                                        href="{{ route('parent.notices') }}">View All Notices</a></li>
                            </ul>
                        </div>

                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark"
                                id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-4 me-2 text-secondary"></i>
                                <strong class="d-none d-sm-inline">{{ $user->name ?? 'Parent' }}</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2"
                                aria-labelledby="dropdownUser">
                                <li><a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"><i
                                            class="bi bi-box-arrow-right me-2"></i>Sign out</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="p-3 p-md-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0"><i
                            class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button"
                            class="btn-close" data-bs-dismiss="alert"></button></div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0"><i
                            class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}<button type="button"
                            class="btn-close" data-bs-dismiss="alert"></button></div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>