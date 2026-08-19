@extends('backend.pages.layout.master')
@push('b-title', 'Navbar Builder')

@push('styles')
<style>
    .list-group-item {
        cursor: move;
        background: var(--admin-card);
        border: 1px solid var(--admin-border);
        margin-bottom: 5px;
        border-radius: 4px;
        padding: 10px 15px;
    }
    .nested-list {
        min-height: 20px;
        margin-top: 10px;
        padding-left: 20px;
        border-left: 2px dashed var(--admin-border);
    }
    .ghost {
        opacity: 0.4;
        background: var(--green-pale);
    }
    .item-actions {
        float: right;
    }
</style>
@endpush

@section('backend-content')
@include('sweetalert::alert')

<div class="admin-page-header">
    <h1 class="aph-title">Navbar Builder</h1>
    <p class="aph-sub">Manage your site's navigation menu. Drag and drop items to reorder or nest them.</p>
</div>

<div class="row g-4">
    <!-- Add New Item Column -->
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <span class="card-title">Add Menu Item</span>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('navbar.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent_id" value="">
                    
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g. About Us">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" id="itemType" class="form-control" required>
                            <option value="route">App Route (Predefined)</option>
                            <option value="page">Custom HTML Page</option>
                            <option value="custom">Custom URL</option>
                            <option value="dynamic_courses">Dynamic Courses Dropdown</option>
                        </select>
                    </div>

                    <!-- Type Specific Inputs -->
                    <div id="typeRoute" class="mb-3 type-input">
                        <label class="form-label">Select Route</label>
                        <select name="value" class="form-control" id="routeSelect">
                            <option value="home">Home</option>
                            <option value="about.us">About Us</option>
                            <option value="member">Faculties</option>
                            <option value="calendar">Campus Calendar</option>
                            <option value="gallery">Gallery</option>
                            <option value="contact">Contact</option>
                        </select>
                    </div>

                    <div id="typePage" class="mb-3 type-input" style="display:none;">
                        <label class="form-label">Select Page</label>
                        <select name="value" class="form-control" id="pageSelect" disabled>
                            @foreach($pages as $page)
                                <option value="{{ $page->id }}">{{ $page->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="typeCustom" class="mb-3 type-input" style="display:none;">
                        <label class="form-label">URL</label>
                        <input type="text" name="value" id="customInput" class="form-control" placeholder="https://example.com" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">CSS Class (Optional)</label>
                        <input type="text" name="css_class" class="form-control" placeholder="e.g. nav-apply">
                    </div>

                    <button type="submit" class="btn-admin btn-admin-primary w-100">Add to Menu</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Menu Structure Column -->
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header d-flex justify-content-between align-items-center">
                <span class="card-title">Menu Structure</span>
                <form action="{{ route('navbar.reorder') }}" method="POST" id="reorderForm">
                    @csrf
                    <input type="hidden" name="order_data" id="orderData">
                    <button type="button" class="btn-admin btn-admin-sm btn-admin-primary" onclick="saveOrder()">Save Order</button>
                </form>
            </div>
            <div class="admin-card-body">
                <div id="menuList" class="nested-list">
                    @foreach($items as $item)
                        @include('backend.pages.navbar._item', ['item' => $item])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('itemType');
        const inputs = document.querySelectorAll('.type-input');
        const rSelect = document.getElementById('routeSelect');
        const pSelect = document.getElementById('pageSelect');
        const cInput = document.getElementById('customInput');
        
        typeSelect.addEventListener('change', function() {
            inputs.forEach(el => el.style.display = 'none');
            rSelect.disabled = true;
            pSelect.disabled = true;
            cInput.disabled = true;
            
            if(this.value === 'route') {
                document.getElementById('typeRoute').style.display = 'block';
                rSelect.disabled = false;
            } else if(this.value === 'page') {
                document.getElementById('typePage').style.display = 'block';
                pSelect.disabled = false;
            } else if(this.value === 'custom') {
                document.getElementById('typeCustom').style.display = 'block';
                cInput.disabled = false;
            }
        });

        // Initialize Sortable
        const nestedSortables = document.querySelectorAll('.nested-list');
        for (let i = 0; i < nestedSortables.length; i++) {
            new Sortable(nestedSortables[i], {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                ghostClass: 'ghost'
            });
        }
    });

    function saveOrder() {
        const rootList = document.getElementById('menuList');
        const data = serialize(rootList);
        document.getElementById('orderData').value = JSON.stringify(data);
        document.getElementById('reorderForm').submit();
    }

    function serialize(sortableElement) {
        const data = [];
        const items = sortableElement.children;
        for (let i = 0; i < items.length; i++) {
            const item = items[i];
            const id = item.getAttribute('data-id');
            const childList = item.querySelector('.nested-list');
            let children = [];
            if (childList) {
                children = serialize(childList);
            }
            data.push({ id: id, children: children });
        }
        return data;
    }
</script>
@endpush
