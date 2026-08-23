<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th>Exam Name</th>
                <th>Status</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse(App\Models\Exam::latest()->take(5)->get() as $exam)
            <tr>
                <td class="fw-bold">{{ $exam->title }}</td>
                <td>
                    @if($exam->status == 1)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td class="text-end">
                    <a href="{{ route('exam.show', $exam->id) }}" class="btn btn-sm btn-outline-primary">Results</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center text-muted py-3">No exams have been created yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
