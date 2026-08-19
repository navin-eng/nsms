@extends('backend.pages.layout.master')
@push('b-title', 'School Information Settings')

@section('backend-content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">School Information Settings</h3>
            <p class="text-muted mb-0">Control the master school information, contact details, and brand identity.</p>
        </div>
    </div>

    <form action="{{ route('site.settings.sms.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Brand Identity</h5>
                        <div class="mb-3">
                            <label class="form-label">Site Logo</label>
                            <input type="file" name="site_logo" class="form-control" accept="image/*">
                            @if($settings->site_logo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $settings->site_logo) }}" alt="Current Logo" style="max-height: 50px;">
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Site Favicon</label>
                            <input type="file" name="site_favicon" class="form-control" accept="image/*,.ico">
                            @if($settings->site_favicon)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $settings->site_favicon) }}" alt="Current Favicon" style="max-height: 32px;">
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Site Name</label>
                            <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $settings->site_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Short Name</label>
                            <input type="text" name="site_short_name" class="form-control" value="{{ old('site_short_name', $settings->site_short_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tagline / Logo Subtitle</label>
                            <input type="text" name="site_tagline" class="form-control" value="{{ old('site_tagline', $settings->site_tagline) }}" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Preferred Calendar System</label>
                            <select name="calendar_system" class="form-select" required>
                                <option value="AD" {{ old('calendar_system', $settings->calendar_system ?? 'AD') == 'AD' ? 'selected' : '' }}>English (AD)</option>
                                <option value="BS" {{ old('calendar_system', $settings->calendar_system ?? 'AD') == 'BS' ? 'selected' : '' }}>Nepali (BS)</option>
                            </select>
                            <small class="text-muted d-block mt-1">This will change how dates are displayed and inputted across the SMS. Internally, dates will still be saved as English AD to preserve database performance.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Contact Information</h5>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $settings->contact_phone) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings->contact_email) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" name="contact_address" class="form-control" value="{{ old('contact_address', $settings->contact_address) }}">
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-1">Activity Log</h5>
                        <p class="text-muted mb-0">Recent changes made to the site settings.</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>When</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Summary</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                    <td>{{ $log->user_name }}</td>
                                    <td><span class="badge bg-primary">{{ ucfirst($log->action) }}</span></td>
                                    <td>{{ $log->summary }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No settings activity logged yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary px-4">Save School Information</button>
        </div>
    </form>
@endsection
