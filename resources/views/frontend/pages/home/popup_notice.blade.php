@if($popupNotice && !session()->has('popupClosed'))
    <div class="gplc-popup-wrap" id="gplcPopup">
        <div class="gplc-popup">
            <a href="{{ route('popup.close') }}" class="gplc-popup-close" title="Close">&times;</a>
            @if($popupNotice->image)
                <img src="{{ asset($popupNotice->image) }}" alt="{{ $popupNotice->title }}" loading="lazy">
            @endif
            <div class="popup-body">
                <h3>{{ $popupNotice->title }}</h3>
                <a href="{{ url('notice/detail/' . $popupNotice->id) }}" class="btn-gplc">Read More</a>
            </div>
        </div>
    </div>
@endif
