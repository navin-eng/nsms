@extends('frontend.layout.master')
@section('frontend-content')

<div class="page-hero">
    <div class="container">
        <div class="page-hero-content" data-aos="fade-up">
            <h1>All Notices</h1>
            <nav class="breadcrumb-nav">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                Notices
            </nav>
        </div>
    </div>
</div>

<section class="section-block">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-tag">Updates</span>
            <h2 class="section-title mt-2">Notice Board</h2>
            <div class="section-divider center"></div>
            <p class="text-muted mt-3 mb-0">Search announcements, sort recent updates, and filter notices by where they appear on the site.</p>
        </div>

        <div class="notice-board-shell" data-aos="fade-up" data-aos-delay="100">
            <div class="notice-toolbar">
                <input type="search" id="noticeSearch" class="form-control" placeholder="Search notices by title...">
                <select id="noticeFilter" class="form-control">
                    <option value="all">All Types</option>
                    <option value="m">Marquee</option>
                    <option value="p">Popup</option>
                </select>
                <select id="noticeSort" class="form-control">
                    <option value="latest">Latest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="title">Title A-Z</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table align-middle" id="noticeTable">
                    <thead>
                        <tr>
                            <th>Notice</th>
                            <th>Type</th>
                            <th>Published</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notices as $notice)
                            <tr data-title="{{ strtolower($notice->title) }}" data-type="{{ $notice->show_in }}" data-date="{{ optional($notice->created_at)->timestamp ?? 0 }}">
                                <td>
                                    <div class="notice-table-title">
                                        @if($notice->image)
                                            <img src="{{ asset($notice->image) }}" alt="{{ $notice->title }}">
                                        @endif
                                        <div>
                                            <strong>{{ $notice->title }}</strong>
                                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($notice->description), 90) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $notice->show_in === 'p' ? 'bg-warning text-dark' : 'bg-success' }}">
                                        {{ $notice->show_in === 'p' ? 'Popup' : 'Marquee' }}
                                    </span>
                                </td>
                                <td>{{ optional($notice->created_at)->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ url('notice/detail/' . $notice->id) }}" class="btn btn-sm btn-outline-success">Open</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@push('styles')
    <style>
        .notice-board-shell {
            background: #fff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--shadow);
        }
        .notice-toolbar {
            display: grid;
            grid-template-columns: 1.6fr .8fr .8fr;
            gap: 14px;
            margin-bottom: 18px;
        }
        .notice-table-title {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .notice-table-title img {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 14px;
            flex-shrink: 0;
        }
        .notice-table-title p {
            margin: 6px 0 0;
            color: var(--text-muted);
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .notice-toolbar {
                grid-template-columns: 1fr;
            }
            .notice-table-title {
                align-items: flex-start;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (() => {
            const search = document.getElementById('noticeSearch');
            const filter = document.getElementById('noticeFilter');
            const sort = document.getElementById('noticeSort');
            const tbody = document.querySelector('#noticeTable tbody');
            if (!search || !filter || !sort || !tbody) return;

            const applyNoticeFilters = () => {
                const query = search.value.trim().toLowerCase();
                const type = filter.value;
                const rows = [...tbody.querySelectorAll('tr')];

                rows.forEach((row) => {
                    const title = row.dataset.title || '';
                    const rowType = row.dataset.type || '';
                    const matchesQuery = !query || title.includes(query);
                    const matchesType = type === 'all' || rowType === type;
                    row.style.display = matchesQuery && matchesType ? '' : 'none';
                });

                const visibleRows = rows.filter((row) => row.style.display !== 'none');
                visibleRows.sort((a, b) => {
                    if (sort.value === 'title') {
                        return (a.dataset.title || '').localeCompare(b.dataset.title || '');
                    }
                    if (sort.value === 'oldest') {
                        return Number(a.dataset.date) - Number(b.dataset.date);
                    }
                    return Number(b.dataset.date) - Number(a.dataset.date);
                });

                visibleRows.forEach((row) => tbody.appendChild(row));
            };

            search.addEventListener('input', applyNoticeFilters);
            filter.addEventListener('change', applyNoticeFilters);
            sort.addEventListener('change', applyNoticeFilters);
        })();
    </script>
@endpush

@endsection
