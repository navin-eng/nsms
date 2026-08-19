<div class="list-group-item" data-id="{{ $item->id }}">
    <strong>{{ $item->title }}</strong> 
    <span class="badge bg-secondary ms-2">{{ $item->type }}</span>
    <div class="item-actions">
        <a href="{{ route('navbar.destroy', $item->id) }}" class="text-danger" onclick="return confirm('Delete this item?')"><i class="bi bi-trash"></i></a>
    </div>
    <div class="nested-list">
        @if($item->children)
            @foreach($item->children as $child)
                @include('backend.pages.navbar._item', ['item' => $child])
            @endforeach
        @endif
    </div>
</div>
