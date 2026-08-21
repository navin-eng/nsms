@extends('backend.pages.layout.master')
@section('title', 'Library Books')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Library Books Inventory</h5>
        <p class="text-muted small mb-0">Manage all books, copies and their status.</p>
    </div>
    <a href="{{ route('sms.library.books.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add New Book
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('sms.library.books.index') }}" method="GET" class="row g-2">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by title, author or ISBN..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Book Details</th>
                        <th>Category</th>
                        <th>Rack No.</th>
                        <th>Copies (Total/Avail)</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                @if($book->cover_image)
                                    <img src="{{ asset('uploads/library/' . $book->cover_image) }}" alt="Cover" class="rounded me-3" style="width: 40px; height: 55px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary bg-opacity-10 text-secondary rounded d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 55px;">
                                        <i class="bi bi-book fs-4"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $book->title }}</h6>
                                    <span class="text-muted small">By {{ $book->author ?? 'Unknown' }}</span><br>
                                    <span class="badge bg-light text-dark border mt-1">ISBN: {{ $book->isbn ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info">
                                {{ $book->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-medium text-dark">{{ $book->rack_number ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary rounded-pill">{{ $book->total_copies }} Total</span>
                                <span class="badge {{ $book->available_copies > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill">{{ $book->available_copies }} Available</span>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('sms.library.books.show', $book->id) }}" class="btn btn-sm btn-light text-primary me-1" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('sms.library.books.edit', $book->id) }}" class="btn btn-sm btn-light text-secondary me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('sms.library.books.destroy', $book->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this book?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-book-half d-block fs-1 mb-3"></i>
                            No books found in the inventory.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($books->hasPages())
    <div class="card-footer bg-white border-top border-light py-3">
        {{ $books->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
