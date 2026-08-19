@extends('backend.pages.layout.master')
@push('b-title', 'Homepage Layout')

@section('backend-content')
    <style>
        .home-layout-list {
            display: grid;
            gap: 14px;
        }
        .home-layout-item {
            background: #fff;
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 18px;
            padding: 18px;
            display: grid;
            grid-template-columns: 56px 1fr auto auto;
            gap: 16px;
            align-items: center;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
            cursor: grab;
        }
        .home-layout-item.dragging {
            opacity: .7;
        }
        .drag-handle {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            font-size: 1.1rem;
        }
        .layout-meta code {
            display: inline-block;
            margin-top: 4px;
        }
        .layout-order {
            width: 90px;
        }
        @media (max-width: 768px) {
            .home-layout-item {
                grid-template-columns: 48px 1fr;
            }
            .layout-order,
            .layout-visibility {
                grid-column: 2 / -1;
            }
        }
    </style>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="mb-1">Homepage Layout</h3>
            <p class="text-muted mb-0">Choose which sections appear on the index page and drag them into the order you want.</p>
        </div>
    </div>

    <form action="{{ route('home.sections.update') }}" method="POST">
        @csrf
        <div class="home-layout-list" id="homeLayoutList">
            @foreach ($sections as $index => $section)
                <div class="home-layout-item" data-index="{{ $index }}">
                    <div class="drag-handle">
                        <i class="bi bi-grip-vertical"></i>
                    </div>
                    <div class="layout-meta">
                        <input type="hidden" name="sections[{{ $index }}][id]" value="{{ $section->id }}">
                        <input type="hidden" class="sort-order-input" name="sections[{{ $index }}][sort_order]" value="{{ $section->sort_order }}">
                        <input type="text" class="form-control" name="sections[{{ $index }}][label]" value="{{ $section->label }}" required>
                        <code>{{ $section->key }}</code>
                    </div>
                    <div class="layout-order text-muted">
                        Order: <strong class="sort-order-label">{{ $section->sort_order }}</strong>
                    </div>
                    <div class="layout-visibility">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="sections[{{ $index }}][is_visible]" value="1" {{ $section->is_visible ? 'checked' : '' }}>
                            <label class="form-check-label">Visible</label>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary px-4">Save Homepage Layout</button>
        </div>
    </form>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
        <script>
            (() => {
                const list = document.getElementById('homeLayoutList');
                if (!list || typeof Sortable === 'undefined') return;

                const syncOrder = () => {
                    [...list.querySelectorAll('.home-layout-item')].forEach((item, index) => {
                        item.querySelector('.sort-order-input').value = index + 1;
                        item.querySelector('.sort-order-label').textContent = index + 1;
                    });
                };

                Sortable.create(list, {
                    animation: 180,
                    handle: '.drag-handle',
                    ghostClass: 'dragging',
                    onEnd: syncOrder,
                });

                syncOrder();
            })();
        </script>
    @endpush
@endsection
