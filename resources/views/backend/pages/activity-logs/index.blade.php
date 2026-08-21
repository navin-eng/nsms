@extends('backend.pages.layout.master')
@section('title', 'Audit Logs')

@section('backend-content')
<style>
    /* Compact table */
    .log-table td, .log-table th { padding: 6px 10px; font-size: 0.8rem; vertical-align: middle; }
    .log-table th { font-weight: 600; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px; color: var(--bs-secondary-color); white-space: nowrap; }
    .log-table tbody tr:hover { background: var(--bs-tertiary-bg); }

    /* Action badge — uses inline palette instead of broken bg-opacity-15 */
    .action-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 9px; border-radius: 20px; font-size: 0.72rem; font-weight: 600; white-space: nowrap; }
    .action-badge.created  { background: rgba(25,135,84,.12);  color: #146c43; border: 1px solid rgba(25,135,84,.3); }
    .action-badge.updated  { background: rgba(255,193,7,.14);  color: #997404; border: 1px solid rgba(255,193,7,.35); }
    .action-badge.deleted,
    .action-badge.force-deleted { background: rgba(220,53,69,.12); color: #b02a37; border: 1px solid rgba(220,53,69,.3); }
    .action-badge.restored { background: rgba(13,202,240,.12); color: #087990; border: 1px solid rgba(13,202,240,.3); }
    .action-badge.default  { background: rgba(108,117,125,.1); color: #495057; border: 1px solid rgba(108,117,125,.25); }

    /* Responsive filter form */
    .filter-form { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
    .filter-form .form-control,
    .filter-form .form-select { font-size: 0.8rem; height: 34px; padding: 4px 8px; min-width: 0; }
    .filter-form .f-search  { flex: 1 1 180px; max-width: 240px; }
    .filter-form .f-module  { flex: 1 1 120px; max-width: 160px; }
    .filter-form .f-action  { flex: 1 1 110px; max-width: 150px; }
    .filter-form .f-date    { flex: 1 1 130px; max-width: 160px; }
    .filter-form .f-btns    { display: flex; gap: 4px; flex-shrink: 0; }
    .filter-form .btn       { font-size: 0.8rem; height: 34px; padding: 4px 12px; }
    .filter-form .f-count   { margin-left: auto; font-size: 0.78rem; color: var(--bs-secondary-color); white-space: nowrap; }

    @media (max-width: 576px) {
        .filter-form .f-search,
        .filter-form .f-module,
        .filter-form .f-action,
        .filter-form .f-date { flex: 1 1 100%; max-width: 100%; }
        .filter-form .f-count { margin-left: 0; }
        /* Hide less important columns on mobile */
        .log-table .col-ip,
        .log-table .col-num { display: none; }
    }
    @media (max-width: 768px) {
        .log-table .col-ip { display: none; }
    }

    @media print {
        .no-print { display: none !important; }
        body { font-size: 10pt; background: #fff !important; }
        .card { border: none !important; box-shadow: none !important; margin: 0 !important; padding: 0 !important; background: transparent !important; }
        .card-body { padding: 0 !important; }
        a { color: inherit !important; text-decoration: none !important; }
        .log-table td, .log-table th { padding: 4px 6px !important; font-size: 8pt !important; border: 1px solid #ddd !important; }
        .action-badge { padding: 1px 4px !important; font-size: 7pt !important; border-width: 1px !important; }
        .badge { font-size: 7pt !important; padding: 2px 4px !important; border: 1px solid #ccc !important; color: #000 !important; background: transparent !important; }
        .text-truncate { white-space: normal !important; max-width: none !important; }
        @page { margin: 0.5cm; }
    }
</style>

{{-- Page header --}}
<div class="d-flex justify-content-between align-items-start align-items-sm-center flex-wrap gap-2 mb-3">
    <div>
        <h5 class="mb-0 fw-bold">System Audit Logs</h5>
        <p class="text-muted small mb-0">Track all user actions, changes, and system events.</p>
    </div>
    <div class="d-flex gap-2 no-print">
        <a href="{{ route('admin.activity-logs.export', request()->query()) }}" class="btn btn-sm btn-outline-success">
            <i class="bi bi-download me-1"></i>Export CSV
        </a>
        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-printer me-1"></i>Print
        </button>
    </div>
</div>

{{-- Responsive Filter Bar --}}
<div class="card border-0 shadow-sm mb-3 no-print">
    <div class="card-body py-2 px-3">
        <form action="{{ route('admin.activity-logs.index') }}" method="GET" class="filter-form">
            <input type="text" name="search" class="form-control f-search"
                placeholder="Search user, summary, IP…" value="{{ $search }}">

            <select name="module" class="form-select f-module">
                <option value="all" {{ $module=='all'?'selected':'' }}>All Modules</option>
                @foreach($modules as $mod)
                    <option value="{{ $mod }}" {{ $module==$mod?'selected':'' }}>{{ ucfirst($mod) }}</option>
                @endforeach
            </select>

            <select name="action" class="form-select f-action">
                <option value="all" {{ $action=='all'?'selected':'' }}>All Actions</option>
                @foreach($actions as $act)
                    <option value="{{ $act }}" {{ $action==$act?'selected':'' }}>{{ ucfirst($act) }}</option>
                @endforeach
            </select>

            <input type="date" name="from" class="form-control f-date" value="{{ $from }}" title="From date">
            <input type="date" name="to"   class="form-control f-date" value="{{ $to }}"   title="To date">

            <div class="f-btns">
                <button type="submit" class="btn btn-primary"><i class="bi bi-filter"></i> Filter</button>
                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-light">Clear</a>
            </div>

            <span class="f-count">{{ number_format($logs->total()) }} records</span>
        </form>
    </div>
</div>

{{-- Compact Responsive Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($logs->count() > 0)
        <div class="table-responsive">
            <table class="table log-table table-hover mb-0">
                <thead class="bg-body-tertiary">
                    <tr>
                        <th class="ps-3 col-num">#</th>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Module</th>
                        <th>Action</th>
                        <th>Summary</th>
                        <th class="col-ip">IP Address</th>
                        <th class="no-print"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    @php
                        $actionSlug = strtolower(str_replace(' ', '-', $log->action));
                        $actionClass = match(true) {
                            in_array($actionSlug, ['created','create'])       => 'created',
                            in_array($actionSlug, ['updated','update'])       => 'updated',
                            in_array($actionSlug, ['deleted','delete','force-deleted']) => 'deleted',
                            $actionSlug === 'restored'                        => 'restored',
                            default                                           => 'default',
                        };
                    @endphp
                    <tr>
                        <td class="ps-3 text-muted col-num">{{ $logs->firstItem() + $loop->index }}</td>
                        <td class="text-nowrap text-muted">
                            {{ $log->created_at->format('d M Y') }}<br>
                            <span style="font-size:0.72rem;">{{ $log->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="fw-semibold">{{ $log->user_name ?? '—' }}</td>
                        <td>
                            <span class="badge bg-secondary" style="font-size:0.7rem;font-weight:500;">{{ $log->module }}</span>
                        </td>
                        <td>
                            <span class="action-badge {{ $actionClass }}">{{ $log->action }}</span>
                        </td>
                        <td style="max-width:220px;" class="text-truncate" title="{{ $log->summary }}">
                            {{ $log->summary }}
                        </td>
                        <td class="text-muted font-monospace col-ip" style="font-size:0.75rem;">
                            {{ $log->ip_address ?? '—' }}
                        </td>
                        <td class="text-end pe-3 no-print">
                            <a href="{{ route('admin.activity-logs.show', $log->id) }}"
                               class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:0.72rem;">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-3 py-2 border-top no-print">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-journal-x fs-1 d-block mb-3 opacity-50"></i>
            <h6>No Logs Found</h6>
            <p class="small">No activity logs match your current filters.</p>
        </div>
        @endif
    </div>
</div>
@endsection
