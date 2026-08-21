@extends('backend.pages.layout.master')
@section('title', 'Edit Book')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Edit Book</h5>
        <p class="text-muted small mb-0">Update information for "{{ $book->title }}".</p>
    </div>
    <a href="{{ route('sms.library.books.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Inventory
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('sms.library.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Book Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $book->title) }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="library_category_id" class="form-select" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('library_category_id', $book->library_category_id) == $category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Author</label>
                            <input type="text" name="author" class="form-control" value="{{ old('author', $book->author) }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Publisher</label>
                            <input type="text" name="publisher" class="form-control" value="{{ old('publisher', $book->publisher) }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ISBN</label>
                            <input type="text" name="isbn" class="form-control" value="{{ old('isbn', $book->isbn) }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rack / Shelf No.</label>
                            <input type="text" name="rack_number" class="form-control" value="{{ old('rack_number', $book->rack_number) }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $book->price) }}">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Description / Synopsis</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $book->description) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Cover Image</label>
                    <div class="card bg-light border border-dashed rounded text-center py-5 mb-3" style="border-style: dashed !important; border-width: 2px !important;">
                        @if($book->cover_image)
                            <img src="{{ asset('uploads/library/' . $book->cover_image) }}" alt="Cover" class="img-fluid rounded mb-2" style="max-height: 150px; object-fit: contain;">
                            <p class="text-muted small mb-0">Current Cover</p>
                        @else
                            <i class="bi bi-image text-muted fs-1 mb-2"></i>
                            <p class="text-muted small mb-0">No image selected</p>
                        @endif
                    </div>
                    <input type="file" name="cover_image" class="form-control" accept="image/png, image/jpeg, image/jpg">
                    <div class="form-text small">Leave blank to keep existing cover. Accepted formats: JPG, PNG. Max size: 2MB.</div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-1"></i> Update Book Details</button>
            </div>
        </form>
    </div>
</div>
@endsection
