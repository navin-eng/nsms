@if($marqueeNotice)
    <div class="gplc-ticker">
        <div class="gplc-ticker-label">
            <i class="fa-solid fa-bell"></i> Latest Notice
        </div>
        <marquee
            behavior="scroll"
            direction="left"
            scrollamount="7"
            class="notice-marquee"
            onmouseover="this.stop();"
            onmouseout="this.start();"
            ontouchstart="this.stop();"
            ontouchend="this.start();"
        >
            <a href="{{ url('notice/detail/' . $marqueeNotice->id) }}">{{ $marqueeNotice->title }}</a>
        </marquee>
    </div>
@endif
