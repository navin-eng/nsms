@php
    $siteSettings = \App\Models\SiteSetting::current();
@endphp
{{-- ===== TOP BAR ===== --}}
@if($siteSettings->show_topbar ?? true)
<div class="gplc-topbar">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="topbar-info d-flex align-items-center gap-3">
            <span><i class="fas fa-phone-alt"></i> {{ $siteSettings->contact_phone }}</span>
            <span class="d-none d-md-flex"><i class="fas fa-envelope"></i> {{ $siteSettings->contact_email }}</span>
            <span class="d-none d-lg-flex"><i class="fas fa-map-marker-alt"></i> {{ $siteSettings->contact_address }}</span>
        </div>
        <div class="topbar-actions d-flex align-items-center gap-2">
            <div class="topbar-social d-flex gap-1">
                <a href="{{ $siteSettings->facebook_url }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="{{ $siteSettings->youtube_url }}" target="_blank"><i class="fab fa-youtube"></i></a>
                <a href="https://wa.me/{{ $siteSettings->whatsapp_number }}" target="_blank"><i class="fab fa-whatsapp"></i></a>
            </div>
            <a href="{{ route('secure.login') }}" class="portal-btn ms-2" title="NSMS & Accounting Portal">
                <i class="fas fa-shield-alt"></i> NSMS Portal
            </a>
            @if(!empty($siteSettings->header_button_text) && !empty($siteSettings->header_button_url))
                <a href="{{ $siteSettings->header_button_url }}" class="portal-btn portal-btn-secondary" target="_blank">
                    <i class="fas fa-arrow-up-right-from-square"></i> {{ $siteSettings->header_button_text }}
                </a>
            @endif
            <a href="#" class="portal-btn theme-toggle-btn ms-2" title="Toggle Theme">
                <i class="fa-solid fa-moon theme-toggle-icon"></i>
            </a>
        </div>
    </div>
</div>
@endif

{{-- ===== MAIN HEADER ===== --}}
<header class="gplc-header" id="gplcHeader">
    <div class="container">
        <div class="header-inner">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="gplc-logo">
                <img src="{{ \App\Models\SiteSetting::current()->site_logo ? asset('storage/' . \App\Models\SiteSetting::current()->site_logo) : asset('backend/images/logo.png') }}" alt="GPLC Logo" loading="lazy">
                <div class="gplc-logo-text">
                    <span class="college-name">{{ $siteSettings->site_name }}</span>
                    <span class="affiliation">{{ $siteSettings->site_tagline }}</span>
                </div>
            </a>

            {{-- Navigation --}}
            <nav class="gplc-nav" id="gplcNav">
                <ul>
                    @foreach($navbarItems as $item)
                        @if($item->type == 'dynamic_courses')
                            <li class="dropdown">
                                <a href="#" class="{{ request()->segment(1)=='course' ? 'active' : '' }} {{ $item->css_class }}">
                                    {{ $item->title }} <i class="fas fa-chevron-down" style="font-size:9px;margin-left:3px;"></i>
                                </a>
                                <ul class="dropdown-menu-gplc">
                                    @forelse($navCourses as $nc)
                                        <li>
                                            <a href="{{ url('course/' . $nc->slug) }}">
                                                <i class="fas fa-graduation-cap"></i> {{ $nc->name }}
                                            </a>
                                        </li>
                                    @empty
                                        <li><a href="#">No courses yet</a></li>
                                    @endforelse
                                </ul>
                            </li>
                        @elseif($item->children->count() > 0)
                            <li class="dropdown">
                                <a href="#" class="{{ $item->css_class }}">
                                    {{ $item->title }} <i class="fas fa-chevron-down" style="font-size:9px;margin-left:3px;"></i>
                                </a>
                                <ul class="dropdown-menu-gplc">
                                    @foreach($item->children as $child)
                                        <li>
                                            <a href="{{ $child->getActualUrl() }}" target="{{ $child->target }}" class="{{ $child->css_class }}">
                                                @if($child->css_class == 'nav-apply') <i class="fas fa-paper-plane"></i> @endif
                                                {{ $child->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ $item->getActualUrl() }}" target="{{ $item->target }}" class="{{ $item->css_class }} {{ (request()->url() == $item->getActualUrl()) ? 'active' : '' }}">
                                    @if($item->css_class == 'nav-apply')
                                        <i class="fas fa-paper-plane"></i>
                                    @endif
                                    {{ $item->title }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </nav>

            {{-- Hamburger --}}
            <button class="gplc-hamburger" id="gplcHam" aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>
