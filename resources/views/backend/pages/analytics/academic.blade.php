@extends("backend.pages.layout.master")
@section("title", "Academic Analytics")
@section("backend-content")

    <div class="admin-page-header">
        <div>
            <a href="{{ route('sms.dashboard') }}" class="text-decoration-none text-muted mb-1 d-block small">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
            <h1 class="aph-title"><i class="bi bi-mortarboard me-2 text-primary"></i> Academic Performance</h1>
            <p class="aph-sub">Analytics on student exam results</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <span class="card-title mb-0">Overall Pass/Fail Ratio</span>
                    <span class="badge bg-light text-dark float-end">Total Records: {{ $totalMarks }}</span>
                </div>
                <div class="admin-card-body d-flex justify-content-center align-items-center"
                    style="position: relative; height:300px;">
                    <canvas id="passFailChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <span class="card-title mb-0">Top Performing Subjects</span>
                    <span class="badge bg-light text-dark float-end">Average Scores</span>
                </div>
                <div class="admin-card-body">
                    <canvas id="subjectChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Pass/Fail Doughnut Chart
            const pfCtx = document.getElementById('passFailChart').getContext('2d');
            const pfLabels = {!! json_encode($passFailChart['labels']) !!};
            const pfData = {!! json_encode($passFailChart['data']) !!};

            new Chart(pfCtx, {
                type: 'doughnut',
                data: {
                    labels: pfLabels,
                    datasets: [{
                        data: pfData,
                        backgroundColor: ['#198754', '#dc3545'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // Subject Bar Chart
            const subCtx = document.getElementById('subjectChart').getContext('2d');
            const subLabels = {!! json_encode($subjectChart['labels']) !!};
            const subData = {!! json_encode($subjectChart['data']) !!};

            new Chart(subCtx, {
                type: 'bar',
                data: {
                    labels: subLabels,
                    datasets: [{
                        label: 'Average Score',
                        data: subData,
                        backgroundColor: 'rgba(13, 110, 253, 0.7)',
                        borderColor: '#0d6efd',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, max: 100 }
                    }
                }
            });
        });
    </script>
@endsection