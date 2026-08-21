@extends('backend.pages.layout.master')
@section('title', 'Communication Logs')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Communication Logs</h5>
        <p class="text-muted small mb-0">History of all sent SMS, Emails, and Push Notifications.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.communications.compose') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-pencil-square me-1"></i>Compose
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($logs->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="font-size:0.85rem;">
                <thead class="bg-light text-muted" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Type</th>
                        <th>Recipient</th>
                        <th>Message</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td class="ps-4 text-nowrap text-muted">
                            {{ $log->created_at->format('d M Y') }}<br>
                            <span style="font-size:0.75rem;">{{ $log->created_at->format('h:i:s A') }}</span>
                        </td>
                        <td>
                            @if($log->type == 'sms') <span class="badge bg-primary">SMS</span>
                            @elseif($log->type == 'email') <span class="badge bg-danger">Email</span>
                            @elseif($log->type == 'push') <span class="badge bg-info text-dark">Push</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $log->recipient_type ? class_basename($log->recipient_type) : 'Manual/Bulk' }}</div>
                            <div class="text-muted small font-monospace">ID: {{ $log->recipient_id ?? 'N/A' }}</div>
                        </td>
                        <td style="max-width:300px;">
                            @if($log->subject)
                                <div class="fw-semibold text-truncate mb-1">{{ $log->subject }}</div>
                            @endif
                            <div class="text-muted text-truncate small">{{ $log->message }}</div>
                        </td>
                        <td>
                            @if($log->status == 'sent')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Sent</span>
                            @elseif($log->status == 'failed')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25" title="{{ $log->error_message }}">Failed</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
            <h6>No Communications Sent Yet</h6>
            <p class="small mb-0">Sent messages will appear here.</p>
        </div>
        @endif
    </div>
</div>
@endsection
