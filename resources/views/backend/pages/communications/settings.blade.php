@extends('backend.pages.layout.master')
@section('title', 'Communication Settings')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Communication System & Gateway Configuration</h5>
        <p class="text-muted small mb-0">Configure your third-party SMS providers (Sparrow SMS, NTC), Email SMTP, and Push Notification settings.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.communications.compose') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-send me-1"></i>Compose Message
        </a>
        <a href="{{ route('admin.communications.logs') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-clock-history me-1"></i>Logs
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- SMS Gateway Configuration --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-2 bg-primary bg-opacity-10 text-primary rounded-circle">
                        <i class="bi bi-chat-dots-fill fs-5"></i>
                    </span>
                    <div>
                        <h6 class="mb-0 fw-bold">SMS Gateway Provider</h6>
                        <small class="text-muted">Configure active SMS gateway credentials</small>
                    </div>
                </div>
                @if($smsConfig && $smsConfig->is_active)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
                        <i class="bi bi-check-circle-fill me-1"></i>Active ({{ strtoupper($smsConfig->driver) }})
                    </span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary border px-3 py-1 rounded-pill">
                        <i class="bi bi-x-circle me-1"></i>Inactive
                    </span>
                @endif
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.communications.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="channel" value="sms">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select SMS Provider <span class="text-danger">*</span></label>
                        <select name="driver" id="smsDriverSelect" class="form-select" required>
                            <option value="sparrow" {{ ($smsConfig->driver ?? 'sparrow') == 'sparrow' ? 'selected' : '' }}>Sparrow SMS (Nepal)</option>
                            <option value="ntc" {{ ($smsConfig->driver ?? '') == 'ntc' ? 'selected' : '' }}>NTC SMS Gateway (Nepal Telecom)</option>
                            <option value="dummy" {{ ($smsConfig->driver ?? '') == 'dummy' ? 'selected' : '' }}>Dummy / Mock (Logs to system only)</option>
                        </select>
                        <small class="text-muted">Select the gateway provider you have purchased credits from.</small>
                    </div>

                    {{-- Sparrow Fields --}}
                    <div id="sparrowFields" class="driver-fields p-3 bg-light rounded-3 mb-3 border">
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-key me-1"></i>Sparrow SMS Credentials</h6>
                        <div class="mb-3">
                            <label class="form-label fw-medium">API Token <span class="text-danger">*</span></label>
                            <input type="text" name="config[token]" class="form-control" value="{{ ($smsConfig->driver ?? '') == 'sparrow' ? ($smsConfig->config['token'] ?? '') : '' }}" placeholder="e.g. v2_xxxxxx...">
                            <small class="text-muted">Generated from Sparrow SMS developer portal.</small>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-medium">Sender Identity (From / Sender ID) <span class="text-danger">*</span></label>
                            <input type="text" name="config[identity]" class="form-control" value="{{ ($smsConfig->driver ?? '') == 'sparrow' ? ($smsConfig->config['identity'] ?? '') : '' }}" placeholder="e.g. TheSchoolName">
                            <small class="text-muted">Approved sender name/identity provided by Sparrow SMS.</small>
                        </div>
                    </div>

                    {{-- NTC Fields --}}
                    <div id="ntcFields" class="driver-fields p-3 bg-light rounded-3 mb-3 border" style="display: none;">
                        <h6 class="fw-bold text-primary mb-3"><i class="bi bi-broadcast me-1"></i>NTC SMS Credentials</h6>
                        <div class="mb-3">
                            <label class="form-label fw-medium">API Token / Secret Key <span class="text-danger">*</span></label>
                            <input type="text" name="config[token]" class="form-control" value="{{ ($smsConfig->driver ?? '') == 'ntc' ? ($smsConfig->config['token'] ?? '') : '' }}" placeholder="NTC Gateway Token">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Sender ID / Masking</label>
                            <input type="text" name="config[identity]" class="form-control" value="{{ ($smsConfig->driver ?? '') == 'ntc' ? ($smsConfig->config['identity'] ?? '') : '' }}" placeholder="e.g. NTC_ALERT">
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-medium">API Endpoint URL</label>
                            <input type="text" name="config[api_url]" class="form-control" value="{{ ($smsConfig->driver ?? '') == 'ntc' ? ($smsConfig->config['api_url'] ?? 'https://sms.ntc.net.np/api/v1/send') : 'https://sms.ntc.net.np/api/v1/send' }}">
                        </div>
                    </div>

                    {{-- Dummy Note --}}
                    <div id="dummyFields" class="driver-fields p-3 bg-light rounded-3 mb-3 border" style="display: none;">
                        <div class="text-muted small">
                            <i class="bi bi-info-circle me-1"></i> Dummy driver does not send actual SMS. It records messages in the communication database logs and Laravel log files for testing and staging environments.
                        </div>
                    </div>

                    <div class="mb-4 form-check form-switch p-3 border rounded-3 bg-white">
                        <input class="form-check-input ms-0 me-3" type="checkbox" name="is_active" id="smsActive" value="1" {{ ($smsConfig->is_active ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="smsActive">
                            Enable SMS Gateway
                            <div class="small fw-normal text-muted">When active, automated notifications (e.g. absences) and manual SMS will be sent through this gateway.</div>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i>Save SMS Configuration
                    </button>
                </form>
            </div>
        </div>

        {{-- Email & Push Cards --}}
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-envelope-fill text-danger fs-5"></i>
                            <h6 class="mb-0 fw-bold">Email (SMTP)</h6>
                        </div>
                        <p class="text-muted small mb-3">Email settings are pulled from standard Laravel <code>.env</code> mail configuration.</p>
                        <span class="badge bg-light text-dark border">Driver: {{ config('mail.default', 'smtp') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-bell-fill text-warning fs-5"></i>
                            <h6 class="mb-0 fw-bold">Push Notifications</h6>
                        </div>
                        <p class="text-muted small mb-3">Firebase Cloud Messaging (FCM) push notification ready for future mobile apps.</p>
                        <span class="badge bg-light text-dark border">FCM Ready</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Test SMS Gateway Sandbox --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm border-top border-primary border-3">
            <div class="card-header bg-white py-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-2 bg-success bg-opacity-10 text-success rounded-circle">
                        <i class="bi bi-send-check-fill fs-5"></i>
                    </span>
                    <div>
                        <h6 class="mb-0 fw-bold">Instant SMS Gateway Tester</h6>
                        <small class="text-muted">Test your active configuration with a live SMS</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.communications.test-sms') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Destination Mobile Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-phone"></i></span>
                            <input type="text" name="phone" class="form-control" required placeholder="e.g. 9841XXXXXX or 9801XXXXXX">
                        </div>
                        <small class="text-muted">Enter a valid 10-digit mobile number.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Test Message Body <span class="text-danger">*</span></label>
                        <textarea name="test_message" class="form-control" rows="3" required placeholder="This is a test SMS from our School Management System.">Test SMS from School System: Gateway is configured and working!</textarea>
                    </div>

                    <div class="alert alert-info small d-flex align-items-center mb-4">
                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                        <div>Please make sure your SMS Gateway is <strong>Saved</strong> and <strong>Enabled</strong> before sending a test SMS.</div>
                    </div>

                    <button type="submit" class="btn btn-outline-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-broadcast me-1"></i>Send Test SMS Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    function toggleDriverFields() {
        var selected = $('#smsDriverSelect').val();
        $('.driver-fields').hide();
        if (selected === 'sparrow') {
            $('#sparrowFields').slideDown(150);
        } else if (selected === 'ntc') {
            $('#ntcFields').slideDown(150);
        } else if (selected === 'dummy') {
            $('#dummyFields').slideDown(150);
        }
    }

    $('#smsDriverSelect').on('change', toggleDriverFields);
    toggleDriverFields();
});
</script>
@endpush
@endsection
