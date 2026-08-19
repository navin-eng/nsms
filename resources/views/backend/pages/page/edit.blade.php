@extends('backend.pages.layout.master')
@push('b-title', 'Edit Page')
@section('backend-content')
    <div class="row">
        <div class="mb-3">
            <a href="{{ route('page.table') }}" class="btn btn-success">&nbsp;&nbsp;All Pages</a>
        </div>
        <h5 class="h4" style="text-align: center; margin:10px 0;">Edit Page: {{ $page->title }}</h5>
    </div>
    <br>
    <form action="{{ route('page.update', $page->id) }}" method="POST" style="width:100%;">
        @csrf
        <div class="row">
            <div class="col-md-8 col-12">
                <div class="mb-3">
                    <label class="form-label">Page Title</label>
                    <input type="text" name="title" value="{{ old('title', $page->title) }}" class="form-control"
                        placeholder="Enter page title" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">HTML Content</label>
                    <div style="border:1px solid #ced4da; border-radius:4px; overflow:hidden;">
                        <div id="toolbar" style="background:#f8f9fa; padding:8px; border-bottom:1px solid #ced4da; display:flex; flex-wrap:wrap; gap:4px;">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('h2')">H2</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('h3')">H3</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('p')">P</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('strong')"><b>B</b></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('em')"><i>I</i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('ul')">UL</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('ol')">OL</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('li')">LI</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('a href=\'#\'')">Link</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('img src=\'\' alt=\'\'')">IMG</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('div class=\'\'')">DIV</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('table class=\'table table-bordered\'')">Table</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('tr')">TR</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('td')">TD</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('span')">Span</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('br /')">BR</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertTag('hr /')">HR</button>
                        </div>
                        <textarea id="htmlEditor" name="content" rows="22"
                            style="width:100%; border:none; padding:12px; font-family:monospace; font-size:13px; resize:vertical; outline:none; background:#1e1e2e; color:#cdd6f4;">{{ old('content', $page->content) }}</textarea>
                    </div>
                    <small class="text-muted">Write raw HTML. Use the toolbar buttons to insert common tags.</small>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card" style="border:1px solid #dee2e6;">
                    <div class="card-header"><strong>Preview</strong></div>
                    <div class="card-body" id="previewBox" style="min-height:200px; overflow:auto; font-size:14px;"></div>
                </div>
                <br>
                <div class="mb-2">
                    <label class="form-label">Public URL</label>
                    <input type="text" class="form-control" value="{{ url('/page/' . $page->slug) }}" readonly>
                </div>
                <button type="button" class="btn btn-outline-info w-100" onclick="updatePreview()">Refresh Preview</button>
            </div>
        </div>
        <div class="mb-3" style="margin:16px 0;">
            <button type="submit" class="btn btn-primary px-5">Update Page</button>
        </div>
    </form>

    <script>
        function insertTag(tag) {
            const ta = document.getElementById('htmlEditor');
            const start = ta.selectionStart;
            const end = ta.selectionEnd;
            const selected = ta.value.substring(start, end);
            const tagName = tag.split(' ')[0];
            const voidTags = ['br', 'hr', 'img', 'input'];
            let insert;
            if (tag.endsWith('/')) {
                insert = '<' + tag + '>';
            } else if (voidTags.includes(tagName)) {
                insert = '<' + tag + '>';
            } else {
                insert = '<' + tag + '>' + selected + '</' + tagName + '>';
            }
            ta.value = ta.value.substring(0, start) + insert + ta.value.substring(end);
            ta.selectionStart = ta.selectionEnd = start + insert.length;
            ta.focus();
        }

        function updatePreview() {
            document.getElementById('previewBox').innerHTML = document.getElementById('htmlEditor').value;
        }

        document.getElementById('htmlEditor').addEventListener('input', function () {
            updatePreview();
        });

        // Init preview on load
        updatePreview();
    </script>
@endsection
