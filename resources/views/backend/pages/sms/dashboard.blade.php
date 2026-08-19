@extends('backend.pages.layout.master')
@push('b-title', 'School Management System')

@section('backend-content')
<div class="admin-page-header">
  <div>
    <h1 class="aph-title">School Management System</h1>
    <p class="aph-sub">Phase 1 is currently under construction.</p>
  </div>
</div>

<div class="admin-card text-center" style="padding: 60px 20px;">
    <i class="bi bi-cone-striped text-warning" style="font-size: 48px; margin-bottom: 20px;"></i>
    <h2>Coming Soon</h2>
    <p class="text-muted" style="max-width: 500px; margin: 10px auto;">
        The Student Information System, Gradebooks, and Financial modules are currently being built. Please check back later.
    </p>
    <a href="{{ route('admin.portal') }}" class="btn-admin btn-admin-primary mt-4">
        <i class="bi bi-arrow-left"></i> Return to Portal
    </a>
</div>
@endsection
