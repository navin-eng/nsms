<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th>Person</th>
                <th>Role</th>
                <th>Date</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($upcomingBirthdays ?? [] as $bday)
            @php
                $avatar = 'assets/backend/images/avatar.png';
                if (($bday['gender'] ?? '') === 'Male' || ($bday['gender'] ?? '') === 'M') {
                    $avatar = 'assets/backend/images/avatar-male.png';
                } elseif (($bday['gender'] ?? '') === 'Female' || ($bday['gender'] ?? '') === 'F') {
                    $avatar = 'assets/backend/images/avatar-female.png';
                }
                $photoUrl = $bday['photo'] ? asset($bday['photo']) : asset($avatar);
            @endphp
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="{{ $photoUrl }}" class="rounded-circle me-2 object-fit-cover" width="35" height="35" alt="">
                        <span class="fw-bold">{{ $bday['name'] }}</span>
                    </div>
                </td>
                <td class="text-muted small">{{ $bday['type'] }}</td>
                <td>
                    <span class="badge bg-light text-dark border">{{ $bday['date'] }}</span>
                    @if($bday['days_left'] == 0)
                        <span class="badge bg-danger ms-1">Today!</span>
                    @else
                        <small class="text-muted ms-1">In {{ $bday['days_left'] }} days</small>
                    @endif
                </td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary" onclick="openBirthdayModal('{{ addslashes($bday['name']) }}', '{{ addslashes($bday['type']) }}', '{{ $photoUrl }}')">
                        <i class="bi bi-magic"></i> Wish
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted py-4">
                    <i class="bi bi-calendar-x fs-3 d-block mb-2 text-light"></i>
                    No upcoming birthdays in the next 7 days.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
