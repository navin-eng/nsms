@extends('backend.pages.layout.master')
@push('b-title', 'Messages')

@push('styles')
<style>
    .message-hero {
        background:
            radial-gradient(circle at top right, rgba(82, 183, 136, 0.15), transparent 28%),
            linear-gradient(135deg, #f8fffb 0%, #f4f7ff 100%);
    }

    .message-kicker {
        display: inline-flex;
        padding: 8px 12px;
        border-radius: 999px;
        background: #eaf7ef;
        color: #157347;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .message-title {
        font-size: 2rem;
        font-weight: 800;
        color: #132238;
    }

    .message-stat-card,
    .message-table-card {
        border-radius: 24px;
        overflow: hidden;
    }

    .message-stat {
        padding: 22px;
        border-radius: 20px;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        border: 1px solid #e3ebf4;
        box-shadow: 0 12px 30px rgba(19, 34, 56, 0.05);
        height: 100%;
    }

    .message-stat .label {
        display: block;
        margin-bottom: 8px;
        color: #5b7694;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .message-stat .value {
        display: block;
        color: #132238;
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
    }

    .message-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .message-search {
        max-width: 360px;
        width: 100%;
        border-radius: 16px;
        border: 1px solid #dbe6f2;
        padding: 13px 16px;
        box-shadow: none;
    }

    .message-search:focus {
        border-color: #7cb4ff;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.08);
        outline: none;
    }

    .message-filter-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .message-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .message-filter-btn {
        border: 1px solid #dbe6f2;
        background: #fff;
        color: #5b7694;
        border-radius: 999px;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 700;
    }

    .message-filter-btn.active {
        background: #157347;
        border-color: #157347;
        color: #fff;
    }

    .message-bulk-bar {
        display: none;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        padding: 14px 16px;
        border: 1px solid #dfe8f2;
        border-radius: 18px;
        background: #f8fbff;
        margin-bottom: 18px;
    }

    .message-bulk-bar.show {
        display: flex;
    }

    .message-table-wrap {
        overflow-x: auto;
    }

    .message-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .message-table th,
    .message-table td {
        padding: 16px 18px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
    }

    .message-table th {
        font-size: 12px;
        font-weight: 800;
        color: #57718f;
        text-transform: uppercase;
        letter-spacing: .08em;
        background: #f8fbff;
    }

    .message-table .select-col {
        width: 52px;
        text-align: center;
    }

    .message-row-unread {
        background: rgba(234, 247, 239, 0.55);
    }

    .message-name {
        font-weight: 800;
        color: #17324d;
    }

    .message-email {
        font-size: 13px;
        color: #5f7896;
    }

    .message-preview {
        max-width: 380px;
        color: #4c6580;
    }

    .message-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }

    .message-badge-unread {
        background: #fff0c2;
        color: #8a6500;
    }

    .message-badge-read {
        background: #eaf7ef;
        color: #157347;
    }

    .message-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .message-action-btn {
        border-radius: 12px;
    }

    .message-modal-body dl {
        margin: 0;
        display: grid;
        gap: 14px;
    }

    .message-modal-body dt {
        font-size: 12px;
        font-weight: 800;
        color: #5b7694;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: 4px;
    }

    .message-modal-body dd {
        margin: 0;
        color: #17324d;
        line-height: 1.8;
    }
</style>
@endpush

@section('backend-content')
<div class="card border-0 shadow-sm message-hero mb-4">
    <div class="card-body p-4 p-lg-5">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <span class="message-kicker">Inbox</span>
                <h3 class="message-title mb-2">Contact Messages</h3>
                <p class="text-muted mb-0">Review public contact submissions, mark them as read, and keep the inbox organized.</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="message-stat">
            <span class="label">Total Messages</span>
            <span class="value">{{ $messageStats['total'] }}</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="message-stat">
            <span class="label">Unread</span>
            <span class="value">{{ $messageStats['unread'] }}</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="message-stat">
            <span class="label">Read</span>
            <span class="value">{{ $messageStats['read'] }}</span>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="message-stat">
            <span class="label">Today</span>
            <span class="value">{{ $messageStats['today'] }}</span>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm message-table-card">
    <div class="card-body p-4">
        <div class="message-toolbar mb-4">
            <div>
                <h4 class="mb-1">Message Inbox</h4>
                <p class="text-muted mb-0">Open each message to see full details and contact information.</p>
            </div>
            <input type="search" id="messageSearchInput" class="message-search" placeholder="Search by name, email, phone, or message">
        </div>

        <div class="message-filter-row">
            <div class="message-filters">
                <button type="button" class="message-filter-btn active" data-filter="all">All</button>
                <button type="button" class="message-filter-btn" data-filter="unread">Unread</button>
                <button type="button" class="message-filter-btn" data-filter="read">Read</button>
                <button type="button" class="message-filter-btn" data-filter="today">Today</button>
            </div>
        </div>

        <form action="{{ route('message.bulk-destroy') }}" method="POST" id="bulkDeleteForm">
            @csrf
            <div class="message-bulk-bar" id="messageBulkBar">
                <span><strong id="selectedMessageCount">0</strong> message(s) selected</span>
                <button type="submit" class="btn btn-danger btn-sm">Delete Selected</button>
                <button type="button" class="btn btn-light btn-sm border" id="clearMessageSelectionBtn">Clear Selection</button>
            </div>

        <div class="message-table-wrap">
            <table class="message-table">
                <thead>
                    <tr>
                        <th class="select-col">
                            <input type="checkbox" id="selectAllMessages">
                        </th>
                        <th>#</th>
                        <th>Sender</th>
                        <th>Preview</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="messageTableBody">
                    @forelse($messages as $message)
                        <tr class="{{ $message->is_read ? '' : 'message-row-unread' }}" data-search="{{ strtolower($message->name . ' ' . $message->email . ' ' . $message->phone . ' ' . $message->address . ' ' . $message->desc) }}" data-status="{{ $message->is_read ? 'read' : 'unread' }}" data-today="{{ optional($message->created_at)->isToday() ? '1' : '0' }}">
                            <td class="select-col">
                                <input type="checkbox" class="message-select-checkbox" name="message_ids[]" value="{{ $message->id }}">
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="message-name">{{ $message->name }}</div>
                                <div class="message-email">{{ $message->email }}</div>
                            </td>
                            <td class="message-preview">{{ \Illuminate\Support\Str::limit($message->desc, 110) }}</td>
                            <td>
                                <div>{{ $message->phone ?: 'No phone' }}</div>
                                <small class="text-muted">{{ $message->address ?: 'No address' }}</small>
                            </td>
                            <td>
                                <span class="message-badge {{ $message->is_read ? 'message-badge-read' : 'message-badge-unread' }}">
                                    {{ $message->is_read ? 'Read' : 'Unread' }}
                                </span>
                            </td>
                            <td>{{ optional($message->created_at)->format('d M Y, h:i A') }}</td>
                            <td>
                                <div class="message-actions">
                                    <button type="button" class="btn btn-outline-primary btn-sm message-action-btn" data-bs-toggle="modal" data-bs-target="#messageModal{{ $message->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Reply to your message') }}" class="btn btn-outline-secondary btn-sm message-action-btn">
                                        <i class="bi bi-reply"></i>
                                    </a>
                                    <a href="{{ route('message.toggle-read', $message->id) }}" class="btn btn-outline-success btn-sm message-action-btn">
                                        <i class="bi {{ $message->is_read ? 'bi-envelope' : 'bi-envelope-open' }}"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm message-action-btn delete-wrap" data-route="{{ route('message.destroy', $message->id) }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">No messages have been received yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </form>
    </div>
</div>

@foreach($messages as $message)
    <div class="modal fade" id="messageModal{{ $message->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 22px;">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="modal-title fw-bold">{{ $message->name }}</h5>
                        <p class="text-muted mb-0">{{ $message->email }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body message-modal-body pt-3">
                    <dl>
                        <div>
                            <dt>Phone</dt>
                            <dd>{{ $message->phone ?: 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt>Address</dt>
                            <dd>{{ $message->address ?: 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt>Received</dt>
                            <dd>{{ optional($message->created_at)->format('l, d M Y \a\t h:i A') }}</dd>
                        </div>
                        <div>
                            <dt>Message</dt>
                            <dd>{{ $message->desc }}</dd>
                        </div>
                    </dl>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <a href="{{ route('message.toggle-read', $message->id) }}" class="btn btn-outline-success">
                        {{ $message->is_read ? 'Mark Unread' : 'Mark Read' }}
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
    (() => {
        const searchInput = document.getElementById('messageSearchInput');
        const rows = Array.from(document.querySelectorAll('#messageTableBody tr[data-search]'));
        const filterButtons = Array.from(document.querySelectorAll('.message-filter-btn'));
        const selectAllMessages = document.getElementById('selectAllMessages');
        const checkboxes = Array.from(document.querySelectorAll('.message-select-checkbox'));
        const bulkBar = document.getElementById('messageBulkBar');
        const selectedCount = document.getElementById('selectedMessageCount');
        const clearSelectionBtn = document.getElementById('clearMessageSelectionBtn');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');

        let activeFilter = 'all';

        const applyFilters = () => {
            const query = searchInput?.value.trim().toLowerCase() || '';

            rows.forEach((row) => {
                const matchesSearch = row.dataset.search.includes(query);
                const matchesFilter =
                    activeFilter === 'all' ||
                    (activeFilter === 'unread' && row.dataset.status === 'unread') ||
                    (activeFilter === 'read' && row.dataset.status === 'read') ||
                    (activeFilter === 'today' && row.dataset.today === '1');

                row.style.display = matchesSearch && matchesFilter ? '' : 'none';
            });
        };

        const updateBulkBar = () => {
            const checked = checkboxes.filter((checkbox) => checkbox.checked);
            selectedCount.textContent = checked.length;
            bulkBar.classList.toggle('show', checked.length > 0);
            if (selectAllMessages) {
                selectAllMessages.checked = checked.length > 0 && checked.length === checkboxes.length;
            }
        };

        searchInput?.addEventListener('input', () => {
            applyFilters();
        });

        filterButtons.forEach((button) => {
            button.addEventListener('click', () => {
                activeFilter = button.dataset.filter;
                filterButtons.forEach((btn) => btn.classList.toggle('active', btn === button));
                applyFilters();
            });
        });

        selectAllMessages?.addEventListener('change', () => {
            checkboxes.forEach((checkbox) => {
                checkbox.checked = selectAllMessages.checked;
            });
            updateBulkBar();
        });

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', updateBulkBar);
        });

        clearSelectionBtn?.addEventListener('click', () => {
            checkboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });
            if (selectAllMessages) {
                selectAllMessages.checked = false;
            }
            updateBulkBar();
        });

        bulkDeleteForm?.addEventListener('submit', (event) => {
            if (!checkboxes.some((checkbox) => checkbox.checked)) {
                event.preventDefault();
            }
        });

        applyFilters();
        updateBulkBar();
    })();
</script>
@endpush
