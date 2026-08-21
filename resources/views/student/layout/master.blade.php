<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - {{ config('app.name', 'School Management') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Inter', 'Comic Sans MS', sans-serif;
            background-color: #f0f7ff;
        }

        .navbar-brand {
            font-weight: 800;
            color: #0d6efd !important;
            font-size: 1.5rem;
        }

        .back-btn {
            font-weight: bold;
            color: #0d6efd;
            text-decoration: none;
        }

        .back-btn:hover {
            text-decoration: underline;
        }

        /* Playful Card Styles for Dashboard */
        .module-card {
            border-radius: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            display: block;
            background: white;
            border: 4px solid transparent;
        }

        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
            border-color: #0d6efd;
        }

        .module-icon {
            font-size: 4rem;
            margin-bottom: 15px;
        }

        .module-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #333;
        }
    </style>
    @stack('styles')
</head>
@php
    $user = Auth::user();
    $student = \App\Models\Student::where('user_id', $user->id)->first();

    $theme = $student->theme ?? 'default';
    $font = $student->font ?? 'Inter';

    $themeBg = '#f0f7ff';
    $themePrimary = '#0d6efd';
    $themeText = '#333';

    $fontFamily = "'Inter', sans-serif";
    if ($font == 'Comic Sans MS')
        $fontFamily = "'Comic Neue', cursive";
    if ($font == 'Courier New')
        $fontFamily = "'Courier Prime', monospace";
    if ($font == 'Impact')
        $fontFamily = "'Anton', sans-serif";

    if ($theme == 'barbie') {
        $themeBg = '#ffeaf4';
        $themePrimary = '#e83e8c';
    } elseif ($theme == 'ben10') {
        $themeBg = '#e8f5e9';
        $themePrimary = '#198754';
    } elseif ($theme == 'spiderman') {
        $themeBg = '#fce4e4';
        $themePrimary = '#dc3545';
    } elseif ($theme == 'dark') {
        $themeBg = '#212529';
        $themePrimary = '#0dcaf0';
        $themeText = '#f8f9fa';
    } elseif ($theme == 'scifi') {
        $themeBg = '#0b0c10';
        $themePrimary = '#00f2fe';
        $themeText = '#c5c6c7';
    }

    $siteSetting = \App\Models\SiteSetting::first();
@endphp

<body style="font-family: {{ $fontFamily }}; background-color: {{ $themeBg }}; color: {{ $themeText }};">

    <style>
        .no-arrow::after {
            display: none !important;
        }

        .navbar-brand {
            color:
                {{ $themePrimary }}
                !important;
        }

        .back-btn {
            color:
                {{ $themePrimary }}
            ;
        }

        .module-card:hover {
            border-color:
                {{ $themePrimary }}
            ;
        }

        .btn-primary {
            background-color:
                {{ $themePrimary }}
            ;
            border-color:
                {{ $themePrimary }}
            ;
            color:
                {{ in_array($theme, ['scifi', 'dark']) ? '#000' : '#fff' }}
            ;
        }

        .text-primary {
            color:
                {{ $themePrimary }}
                !important;
        }

        .border-primary {
            border-color:
                {{ $themePrimary }}
                !important;
        }

        @if(in_array($theme, ['dark', 'scifi']))
            .bg-white {
                background-color: #1f2833 !important;
                border-color: #495057 !important;
            }

            .card {
                background-color: #1f2833 !important;
                border-color: #495057 !important;
                color: #c5c6c7;
            }

            .card-header,
            .border-bottom {
                border-color: #495057 !important;
            }

            .text-muted {
                color: #8a9ba8 !important;
            }

            .module-card {
                background: #1f2833;
            }

            .module-title {
                color: #f8f9fa;
            }

            .dropdown-menu {
                background-color: #1f2833;
                border-color: #495057;
            }

            .dropdown-item {
                color: #c5c6c7;
            }

            .dropdown-item:hover {
                background-color: #495057;
                color: white;
            }

            .form-control,
            .form-select {
                background-color: #0b0c10;
                color: white;
                border-color: #495057;
            }

        @endif

        @if($theme == 'scifi')
            .module-card {
                box-shadow: 0 0 10px rgba(0, 242, 254, 0.2);
            }

            .module-card:hover {
                box-shadow: 0 0 20px rgba(0, 242, 254, 0.6) !important;
                text-shadow: 0 0 5px rgba(0, 242, 254, 0.5);
            }

        @endif
    </style>

    <!-- Topbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3 shadow-sm sticky-top">
        <div class="container">
            @if(!request()->routeIs('student.dashboard'))
                <a href="{{ route('student.dashboard') }}" class="back-btn me-3 fs-5">
                    <i class="bi bi-arrow-left-circle-fill"></i> Home
                </a>
            @endif

            <a class="navbar-brand d-flex align-items-center" href="{{ route('student.dashboard') }}">
                @if($siteSetting && $siteSetting->site_logo)
                    <img src="{{ asset('storage/' . $siteSetting->site_logo) }}" alt="Logo" height="40"
                        class="me-2 rounded">
                @else
                    <i class="bi bi-backpack-fill text-primary me-2"></i>
                @endif
                <span class="d-none d-md-inline text-truncate"
                    style="max-width: 200px;">{{ $siteSetting->site_name ?? config('app.name', 'School') }}</span>
                <span class="d-inline d-md-none text-truncate"
                    style="max-width: 150px;">{{ $siteSetting->site_short_name ?? substr($siteSetting->site_name ?? config('app.name', 'School'), 0, 15) }}</span>
            </a>

            <div class="ms-auto d-flex align-items-center">
                <!-- Notification Bell -->
                <div class="dropdown me-3">
                    <a href="#" class="position-relative text-dark fs-5 text-decoration-none dropdown-toggle no-arrow"
                        id="dropdownNotifications" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell-fill"></i>
                        @php
                            $unreadNoticesCount = \App\Models\Notice::forStudent($student ?? null)
                                ->where('created_at', '>=', now()->subDays(7))
                                ->count();
                        @endphp
                        @if($unreadNoticesCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
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
                            $recentNotices = \App\Models\Notice::forStudent($student ?? null)
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp
                        @forelse($recentNotices as $n)
                            <li>
                                <a class="dropdown-item rounded-3 p-2 text-wrap small"
                                    href="{{ route('student.notices.show', $n->id) }}">
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
                                href="{{ route('student.notices') }}">View All Notices</a></li>
                    </ul>
                </div>

                <span
                    class="badge bg-warning text-dark border border-warning shadow-sm me-3 fs-6 rounded-pill px-3 py-2">
                    ⭐ {{ $student->points ?? 0 }}
                </span>
                <div class="dropdown">
                    <a href="#"
                        class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark bg-light px-3 py-2 rounded-pill fw-bold"
                        id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        @if($student && $student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" class="rounded-circle me-2" width="32"
                                height="32" style="object-fit: cover;">
                        @else
                            @php
                                $avatars = [
                                    'robot' => '🤖',
                                    'ninja' => '🥷',
                                    'astronaut' => '🧑‍🚀',
                                    'unicorn' => '🦄',
                                    'dinosaur' => '🦖',
                                    'superhero' => '🦸',
                                    'alien' => '👽',
                                    'wizard' => '🧙'
                                ];
                                $avatarIcon = $student->avatar && isset($avatars[$student->avatar]) ? $avatars[$student->avatar] : null;
                            @endphp
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-2 border border-2 border-primary"
                                style="width: 32px; height: 32px; font-size: 1.2rem; line-height: 1;">
                                {{ $avatarIcon ?? substr($student->first_name ?? $user->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="d-none d-sm-inline">{{ $student->first_name ?? $user->name ?? 'Student' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-4"
                        aria-labelledby="dropdownUser">
                        <li>
                            <a class="dropdown-item fw-bold text-dark" href="{{ route('student.profile') }}">
                                <i class="bi bi-person-circle me-2 text-primary"></i>My Profile
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger fw-bold" href="{{ route('admin.logout') }}"><i
                                    class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-4"><i
                    class="bi bi-check-circle-fill me-2 fs-5"></i>{{ session('success') }}<button type="button"
                    class="btn-close mt-1" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-4"><i
                    class="bi bi-exclamation-circle-fill me-2 fs-5"></i>{{ session('error') }}<button type="button"
                    class="btn-close mt-1" data-bs-dismiss="alert"></button></div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>