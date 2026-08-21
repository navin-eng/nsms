@extends('backend.pages.layout.master')
@section('title', 'Add New Book')

@section('backend-content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold">Add New Book</h5>
        <p class="text-muted small mb-0">Register a new book in the library inventory and generate copies.</p>
    </div>
    <a href="{{ route('sms.library.books.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Inventory
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('sms.library.books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Book Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="Enter full book title" value="{{ old('title') }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="library_category_id" class="form-select" required>
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('library_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Author</label>
                            <input type="text" name="author" class="form-control" placeholder="Book Author" value="{{ old('author') }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Publisher</label>
                            <input type="text" name="publisher" class="form-control" placeholder="Publisher Name" value="{{ old('publisher') }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ISBN</label>
                            <input type="text" name="isbn" class="form-control" placeholder="ISBN number" value="{{ old('isbn') }}">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Rack / Shelf No.</label>
                            <input type="text" name="rack_number" class="form-control" placeholder="e.g. A-12" value="{{ old('rack_number') }}">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" value="{{ old('price', 0) }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-primary">Number of Copies <span class="text-danger">*</span></label>
                            <input type="number" name="total_copies" class="form-control border-primary bg-primary bg-opacity-10" min="1" value="{{ old('total_copies', 1) }}" required>
                            <div class="form-text text-muted" style="font-size: 0.75rem;">Barcodes will be auto-generated for each copy.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Description / Synopsis</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Brief description of the book...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Cover Image</label>
                    <div class="card bg-light border border-dashed rounded text-center py-5 mb-3" style="border-style: dashed !important; border-width: 2px !important;">
                        <i class="bi bi-image text-muted fs-1 mb-2"></i>
                        <p class="text-muted small mb-0">No image selected</p>
                    </div>
                    <input type="file" name="cover_image" class="form-control" accept="image/png, image/jpeg, image/jpg">
                    <div class="form-text small">Accepted formats: JPG, PNG. Max size: 2MB.</div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-1"></i> Save Book & Generate Copies</button>
            </div>
        </form>
    </div>
</div>
@endsection
