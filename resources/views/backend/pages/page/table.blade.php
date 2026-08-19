@extends('backend.pages.layout.master')
@push('b-title', 'Pages')
@section('backend-content')
    <div class="row" style="margin-bottom:16px;">
        <div class="col-6">
            <h5 class="h4">Custom Pages</h5>
        </div>
        <div class="col-6 text-end">
            <a href="{{ route('page.add') }}" class="btn btn-primary">+ Add New Page</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Slug / URL</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pages as $index => $page)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $page->title }}</strong></td>
                        <td>
                            <a href="{{ url('/page/' . $page->slug) }}" target="_blank" class="text-primary">
                                /page/{{ $page->slug }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('page.status', $page->id) }}">
                                @if ($page->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </a>
                        </td>
                        <td>{{ $page->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('page.edit', $page->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <a href="{{ route('page.destroy', $page->id) }}" class="btn btn-sm btn-danger deleteBtn"
                               data-href="{{ route('page.destroy', $page->id) }}">Delete</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No pages found. <a href="{{ route('page.add') }}">Add one now.</a></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
