@extends("backend.pages.layout.master")
@section("title", "Attendance Analytics")
@section("backend-content")

<div class="admin-page-header">
    <div>
        <a href="{{ route('sms.dashboard') }}" class="text-decoration-none text-muted mb-1 d-block small">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
        <h1 class="aph-title"><i class="bi bi-calendar-range me-2 text-info"></i> Attendance Analytics</h1>
        <p class="aph-sub">Trends and overall recorded attendance breakdown</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <span class="card-title mb-0">Attendance Trend (Last 7 Days)</span>
                <span class="badge bg-light text-dark float-end">Daily present percentage</span>
            </div>
            <div class="admin-card-body">
                <canvas id="trendChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <span class="card-title mb-0">Status Distribution</span>
            </div>
            <div class="admin-card-body d-flex justify-content-center align-items-center">
                <canvas id="distChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Trend Line Chart
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const trendLabels = {!! json_encode($trendChart['labels']) !!};
        const trendData = {!! json_encode($trendChart['data']) !!};

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Present Rate (%)',
                    data: trendData,
                    borderColor: '#0dcaf0',
                    backgroundColor: 'rgba(13, 202, 240, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, max: 100 }
                }
            }
        });

        // Distribution Doughnut Chart
        const distCtx = document.getElementById('distChart').getContext('2d');
        const distLabels = {!! json_encode($distributionChart['labels']) !!};
        const distData = {!! json_encode($distributionChart['data']) !!};
        
        // Dynamic colors based on standard statuses
        const bgColors = distLabels.map(label => {
            if(label.toLowerCase() === 'present') return '#198754';
            if(label.toLowerCase() === 'absent') return '#dc3545';
            if(label.toLowerCase() === 'late') return '#ffc107';
            if(label.toLowerCase() === 'half_day') return '#0dcaf0';
            return '#6c757d';
        });

        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: distLabels,
                datasets: [{
                    data: distData,
                    backgroundColor: bgColors,
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
    });
</script>
@endsection
