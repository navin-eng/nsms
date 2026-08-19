@extends('backend.pages.layout.master')
@push('b-title', 'College Messages')
@section('backend-content')
    <div class="row" style="margin-bottom:16px;">
        <div class="col-6">
            <h5 class="h4">Messages (Principal / Chairman / Coordinator)</h5>
        </div>
        <div class="col-6 text-end">
            <a href="{{ route('college_message.add') }}" class="btn btn-primary">+ Add Message</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Message (preview)</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($messages as $index => $msg)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if ($msg->image)
                                <img src="{{ asset($msg->image) }}" class="table-backend-image"
                                    style="border-radius:50%; width:55px; height:55px;" alt="">
                            @else
                                <div style="width:55px;height:55px;border-radius:50%;background:#e9ecef;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-person-fill" style="font-size:24px; color:#adb5bd;"></i>
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ $msg->name }}</strong></td>
                        <td><span class="badge bg-info text-dark">{{ $msg->designation }}</span></td>
                        <td>{{ Str::limit($msg->message, 80) }}</td>
                        <td>{{ $msg->order }}</td>
                        <td>
                            <a href="{{ route('college_message.status', $msg->id) }}">
                                @if ($msg->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('college_message.edit', $msg->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <a href="{{ route('college_message.destroy', $msg->id) }}" class="btn btn-sm btn-danger deleteBtn"
                               data-href="{{ route('college_message.destroy', $msg->id) }}">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No messages found. <a href="{{ route('college_message.add') }}">Add one now.</a></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
