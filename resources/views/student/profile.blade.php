@extends('student.layout.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-1">My Profile</h4>
            <p class="text-muted">Your personal and academic details.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" class="rounded-circle shadow-sm"
                                style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #fff;">
                        @else
                            @php
                                $avatars = [
                                    'robot' => '🤖', 'ninja' => '🥷', 'astronaut' => '🧑‍🚀', 
                                    'unicorn' => '🦄', 'dinosaur' => '🦖', 'superhero' => '🦸', 
                                    'alien' => '👽', 'wizard' => '🧙'
                                ];
                                $avatarIcon = $student->avatar && isset($avatars[$student->avatar]) ? $avatars[$student->avatar] : null;
                            @endphp
                            
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center text-primary fw-bold mx-auto border border-primary border-4 shadow-sm"
                                style="width: 120px; height: 120px; font-size: 4rem;">
                                {{ $avatarIcon ?? substr($student->first_name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h4 class="fw-bold mb-1">{{ $student->first_name }} {{ $student->last_name }}</h4>
                    <p class="text-muted mb-3">{{ optional(optional($activeEnrollment)->academicClass)->name ?? 'N/A' }}
                        {{ optional(optional($activeEnrollment)->section)->name ? '(' . optional($activeEnrollment)->section->name . ')' : '' }}
                    </p>

                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-light text-dark border"><i class="bi bi-hash me-1"></i> Roll:
                            {{ optional($activeEnrollment)->roll_no ?? 'N/A' }}</span>
                        <span class="badge bg-light text-dark border"><i class="bi bi-upc-scan me-1"></i> Adm:
                            {{ $student->admission_no }}</span>
                    </div>

                    <hr>

                    <div class="text-start mt-4">
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">Date of Birth</label>
                            <div class="fw-medium">
                                {{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('M d, Y') : 'N/A' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">Gender</label>
                            <div class="fw-medium">{{ $student->gender ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">Blood Group</label>
                            <div class="fw-medium">{{ $student->blood_group ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Guardian Details</h5>
                </div>
                <div class="card-body">
                    @if($student->guardian)
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="text-muted small d-block mb-1">Father's Name</label>
                                <div class="fw-medium">{{ $student->guardian->father_name ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small d-block mb-1">Mother's Name</label>
                                <div class="fw-medium">{{ $student->guardian->mother_name ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small d-block mb-1">Guardian Contact</label>
                                <div class="fw-medium">
                                    {{ $student->guardian->guardian_phone ?? $student->guardian->father_phone ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small d-block mb-1">Guardian Email</label>
                                <div class="fw-medium">{{ $student->guardian->guardian_email ?? 'N/A' }}</div>
                            </div>
                            <div class="col-12">
                                <label class="text-muted small d-block mb-1">Address</label>
                                <div class="fw-medium">{{ $student->guardian->guardian_address ?? 'N/A' }}</div>
                            </div>
                        </div>
                    @else
                        <div class="text-muted">No guardian information found.</div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Medical & Other Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">Religion</label>
                            <div class="fw-medium">{{ $student->religion ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block mb-1">Category</label>
                            <div class="fw-medium">{{ $student->category ?? 'N/A' }}</div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small d-block mb-1">Medical History</label>
                            <div class="fw-medium">{{ $student->medical_history ?? 'None provided' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-book me-2 text-primary"></i>Library History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Book Details</th>
                                    <th>Issue / Due Date</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Condition & Fine</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($libraryIssues as $issue)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $issue->bookCopy->book->title ?? 'N/A' }}</div>
                                        <div class="text-muted small font-monospace"><i class="bi bi-upc-scan me-1"></i>{{ $issue->bookCopy->barcode ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div class="text-success"><i class="bi bi-arrow-right-circle me-1"></i>{{ $issue->formatted_issue_date }}</div>
                                            <div class="text-danger mt-1"><i class="bi bi-calendar-x me-1"></i>{{ $issue->formatted_due_date }}</div>
                                            @if($issue->return_date)
                                                <div class="text-primary mt-1"><i class="bi bi-arrow-left-circle me-1"></i>Ret: {{ $issue->formatted_return_date }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($issue->status === 'returned')
                                            <span class="badge bg-success">Returned</span>
                                        @elseif($issue->status === 'issued' && $issue->due_date < now())
                                            <span class="badge bg-danger">Overdue</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Issued</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($issue->return_date)
                                            <div class="small text-muted mb-1">
                                                Cond: <strong>{{ ucfirst($issue->bookCopy->condition ?? 'N/A') }}</strong>
                                            </div>
                                            @if($issue->fine_amount > 0)
                                                <div class="small fw-bold text-danger">Fine: ${{ number_format($issue->fine_amount, 2) }} 
                                                    @if($issue->fine_status === 'paid')
                                                        <span class="badge bg-success bg-opacity-10 text-success ms-1">Paid</span>
                                                    @else
                                                        <span class="badge bg-danger bg-opacity-10 text-danger ms-1">Unpaid</span>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="small text-success">No Fine</div>
                                            @endif
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-journal-x fs-2 d-block mb-2"></i>
                                        No library records found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4 rounded-4 border-bottom border-primary border-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-palette-fill text-primary me-2"></i>My Customization</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('student.settings.update') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Choose Theme Theme</label>
                            <div class="row g-2">
                                <div class="col-6 col-md-4">
                                    <input type="radio" class="btn-check" name="theme" id="theme-default" value="default" {{ ($student->theme ?? 'default') == 'default' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 p-3 rounded-4" for="theme-default">
                                        <div class="fs-1 mb-2">🏫</div>
                                        Standard
                                    </label>
                                </div>
                                <div class="col-6 col-md-4">
                                    <input type="radio" class="btn-check" name="theme" id="theme-barbie" value="barbie" {{ ($student->theme ?? 'default') == 'barbie' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger w-100 p-3 rounded-4" for="theme-barbie">
                                        <div class="fs-1 mb-2">🎀</div>
                                        Barbie
                                    </label>
                                </div>
                                <div class="col-6 col-md-4">
                                    <input type="radio" class="btn-check" name="theme" id="theme-ben10" value="ben10" {{ ($student->theme ?? 'default') == 'ben10' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success w-100 p-3 rounded-4" for="theme-ben10">
                                        <div class="fs-1 mb-2">⌚</div>
                                        Ben 10
                                    </label>
                                </div>
                                <div class="col-6 col-md-4">
                                    <input type="radio" class="btn-check" name="theme" id="theme-spiderman"
                                        value="spiderman" {{ ($student->theme ?? 'default') == 'spiderman' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-danger w-100 p-3 rounded-4" for="theme-spiderman">
                                        <div class="fs-1 mb-2">🕷️</div>
                                        Spider-Man
                                    </label>
                                </div>
                                <div class="col-6 col-md-4">
                                    <input type="radio" class="btn-check" name="theme" id="theme-dark" value="dark" {{ ($student->theme ?? 'default') == 'dark' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-dark w-100 p-3 rounded-4" for="theme-dark">
                                        <div class="fs-1 mb-2">🌙</div>
                                        Dark Mode
                                    </label>
                                </div>
                                <div class="col-6 col-md-4">
                                    <input type="radio" class="btn-check" name="theme" id="theme-scifi" value="scifi" {{ ($student->theme ?? 'default') == 'scifi' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-info w-100 p-3 rounded-4" for="theme-scifi">
                                        <div class="fs-1 mb-2">🚀</div>
                                        Sci-Fi
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Avatar Selection -->
                        @if(!$student->photo)
                        <div class="mb-4">
                            <label class="form-label fw-bold">Choose Your Avatar</label>
                            <div class="row g-2">
                                @php
                                    $avatars = [
                                        'robot' => '🤖', 'ninja' => '🥷', 'astronaut' => '🧑‍🚀', 
                                        'unicorn' => '🦄', 'dinosaur' => '🦖', 'superhero' => '🦸', 
                                        'alien' => '👽', 'wizard' => '🧙'
                                    ];
                                @endphp
                                @foreach($avatars as $key => $icon)
                                <div class="col-3 col-md-2">
                                    <input type="radio" class="btn-check" name="avatar" id="avatar-{{ $key }}" value="{{ $key }}" {{ ($student->avatar ?? '') == $key ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 p-2 rounded-4" for="avatar-{{ $key }}">
                                        <div class="fs-2">{{ $icon }}</div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i> Since you haven't uploaded a real photo, pick a fun avatar!</small>
                        </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label fw-bold">Choose Font</label>
                            <select name="font" class="form-select form-select-lg rounded-pill px-4">
                                <option value="Inter" style="font-family: 'Inter', sans-serif;" {{ ($student->font ?? 'Inter') == 'Inter' ? 'selected' : '' }}>Standard Font (Inter)</option>
                                <option value="Comic Sans MS" style="font-family: 'Comic Sans MS', cursive;" {{ ($student->font ?? 'Inter') == 'Comic Sans MS' ? 'selected' : '' }}>Fun Font (Comic Sans)
                                </option>
                                <option value="Courier New" style="font-family: 'Courier New', monospace;" {{ ($student->font ?? 'Inter') == 'Courier New' ? 'selected' : '' }}>Typewriter (Courier)
                                </option>
                                <option value="Impact" style="font-family: 'Impact', sans-serif;" {{ ($student->font ?? 'Inter') == 'Impact' ? 'selected' : '' }}>Bold Font (Impact)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold w-100">
                            <i class="bi bi-magic me-1"></i> Apply Magic!
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection