@extends('backend.pages.layout.master')
@section('title', 'Log Detail')

@section('backend-content')
    <style>
        .detail-label {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--bs-secondary-color);
            margin-bottom: 2px;
        }

        .detail-value {
            font-size: 0.9rem;
            word-break: break-all;
        }

        .diff-key {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--bs-body-color);
        }

        .diff-old {
            background: rgba(var(--bs-danger-rgb), 0.08);
            border-left: 3px solid var(--bs-danger);
            padding: 3px 8px;
            border-radius: 0 4px 4px 0;
            font-size: 0.8rem;
        }

        .diff-new {
            background: rgba(var(--bs-success-rgb), 0.08);
            border-left: 3px solid var(--bs-success);
            padding: 3px 8px;
            border-radius: 0 4px 4px 0;
            font-size: 0.8rem;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                font-size: 10pt;
                background: #fff !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
                margin-bottom: 10px !important;
            }

            .card-header {
                padding: 0 !important;
                background: transparent !important;
                border-bottom: 1px solid #ccc !important;
                margin-bottom: 5px !important;
            }

            .card-body {
                padding: 0 !important;
            }

            .detail-label {
                font-size: 8pt;
                color: #555;
            }

            .detail-value {
                font-size: 10pt;
            }

            .col-sm-4,
            .col-sm-6,
            .col-lg-8,
            .col-lg-4,
            .col-12 {
                padding-bottom: 5px !important;
            }

            .table-sm td,
            .table-sm th {
                padding: 4px 6px !important;
                font-size: 8pt !important;
                border: 1px solid #ddd !important;
            }

            .diff-old,
            .diff-new {
                background: transparent !important;
                border: none !important;
                padding: 0 !important;
            }

            @page {
                margin: 0.5cm;
            }
        }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-light no-print mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to Logs
            </a>
            <h5 class="mb-0 fw-bold">Log Detail</h5>
            <p class="text-muted small mb-0">Full detail of a single audit event.</p>
        </div>
        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary no-print">
            <i class="bi bi-printer me-1"></i>Print
        </button>
    </div>

    <div class="row g-3">
        {{-- Main Info --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-body-tertiary border-bottom py-2 px-3">
                    <span class="fw-semibold small">Event Information</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="detail-label">Timestamp</div>
                            <div class="detail-value">{{ $log->created_at->format('d M Y, h:i:s A') }}</div>
                            <div class="text-muted small">{{ $log->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="detail-label">Module</div>
                            <div class="detail-value"><span class="badge bg-secondary">{{ $log->module }}</span></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="detail-label">Action</div>
                            @php
                                $actionSlug = strtolower(str_replace(' ', '-', $log->action));
                                $badgeStyles = [
                                    'created' => 'background:rgba(25,135,84,.12);color:#146c43;border:1px solid rgba(25,135,84,.3)',
                                    'create' => 'background:rgba(25,135,84,.12);color:#146c43;border:1px solid rgba(25,135,84,.3)',
                                    'updated' => 'background:rgba(255,193,7,.14);color:#997404;border:1px solid rgba(255,193,7,.35)',
                                    'update' => 'background:rgba(255,193,7,.14);color:#997404;border:1px solid rgba(255,193,7,.35)',
                                    'deleted' => 'background:rgba(220,53,69,.12);color:#b02a37;border:1px solid rgba(220,53,69,.3)',
                                    'delete' => 'background:rgba(220,53,69,.12);color:#b02a37;border:1px solid rgba(220,53,69,.3)',
                                    'force-deleted' => 'background:rgba(220,53,69,.12);color:#b02a37;border:1px solid rgba(220,53,69,.3)',
                                    'restored' => 'background:rgba(13,202,240,.12);color:#087990;border:1px solid rgba(13,202,240,.3)',
                                ];
                                $badgeStyle = $badgeStyles[$actionSlug] ?? 'background:rgba(108,117,125,.1);color:#495057;border:1px solid rgba(108,117,125,.25)';
                            @endphp
                            <div>
                                <span
                                    style="{{ $badgeStyle }}; display:inline-block; padding:3px 12px; border-radius:20px; font-size:0.82rem; font-weight:600;">
                                    {{ $log->action }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-label">Summary</div>
                            <div class="detail-value">{{ $log->summary }}</div>
                        </div>
                        @if($log->model_type)
                            <div class="col-sm-6">
                                <div class="detail-label">Record Type</div>
                                <div class="detail-value font-monospace">{{ $log->model_type }}</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="detail-label">Record ID</div>
                                <div class="detail-value font-monospace">{{ $log->model_id ?? '—' }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- User / Session Info --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-body-tertiary border-bottom py-2 px-3">
                    <span class="fw-semibold small">Session & Location</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="detail-label">User</div>
                        <div class="detail-value fw-semibold">{{ $log->user_name ?? '—' }}</div>
                        @if($log->user_id)
                        <div class="text-muted small">ID: {{ $log->user_id }}</div>@endif
                    </div>
                    <div class="mb-3">
                        <div class="detail-label">IP Address</div>
                        <div class="detail-value font-monospace">{{ $log->ip_address ?? '—' }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="detail-label">Location</div>
                        <div class="detail-value">
                            @if($log->location)
                                <i class="bi bi-geo-alt text-danger me-1"></i>{{ $log->location }}
                            @else
                                <span class="text-muted">Not resolved</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="detail-label">Browser / Device</div>
                        <div class="detail-value small text-muted" style="font-size:0.75rem;">{{ $log->user_agent ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Before / After Diff --}}
        @if($log->properties && (isset($log->properties['before']) || isset($log->properties['after'])))
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-body-tertiary border-bottom py-2 px-3">
                        <span class="fw-semibold small">Changes (Before → After)</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0" style="font-size:0.82rem;">
                                <thead class="bg-body-tertiary">
                                    <tr>
                                        <th class="ps-3" style="width:20%">Field</th>
                                        <th style="width:40%"><span class="text-danger"><i
                                                    class="bi bi-dash-circle me-1"></i>Before</span></th>
                                        <th style="width:40%"><span class="text-success"><i
                                                    class="bi bi-plus-circle me-1"></i>After</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $before = $log->properties['before'] ?? [];
                                        $after = $log->properties['after'] ?? [];
                                        $allKeys = array_unique(array_merge(array_keys($before), array_keys($after)));
                                    @endphp
                                    @foreach($allKeys as $key)
                                        <tr>
                                            <td class="ps-3 diff-key">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                                            <td>
                                                @if(array_key_exists($key, $before))
                                                    <div class="diff-old">
                                                        {{ is_array($before[$key]) ? json_encode($before[$key]) : ($before[$key] ?? '<em class="text-muted">null</em>') }}
                                                    </div>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(array_key_exists($key, $after))
                                                    <div class="diff-new">
                                                        {{ is_array($after[$key]) ? json_encode($after[$key]) : ($after[$key] ?? '<em class="text-muted">null</em>') }}
                                                    </div>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-4 text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        No field-level change data recorded for this event.
                        @if(in_array(strtolower($log->action), ['created', 'deleted']))
                            This is expected for {{ strtolower($log->action) }} events.
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection