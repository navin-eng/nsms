@extends("backend.pages.layout.master")
@section("title", "Financial Analytics")
@section("backend-content")

<div class="admin-page-header">
    <div>
        <a href="{{ route('sms.dashboard') }}" class="text-decoration-none text-muted mb-1 d-block small">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
        <h1 class="aph-title"><i class="bi bi-graph-up-arrow me-2 text-success"></i> Financial Health</h1>
        <p class="aph-sub">Revenue vs Inventory Expenses</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Revenue vs Inventory Expenses -->
    <div class="col-lg-8">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <span class="card-title mb-0">Revenue vs. Inventory Expenses</span>
                <span class="badge bg-light text-dark float-end">Last 6 Months Trend</span>
            </div>
            <div class="admin-card-body">
                <canvas id="financialTrendChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <!-- Invoice Status Distribution -->
    <div class="col-lg-4">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <span class="card-title mb-0">Invoice Status</span>
                <span class="badge bg-light text-dark float-end">Distribution by Amount</span>
            </div>
            <div class="admin-card-body d-flex justify-content-center align-items-center">
                <canvas id="invoiceChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Financial Trend (Bar Chart with 2 Datasets)
        const ftCtx = document.getElementById('financialTrendChart').getContext('2d');
        const ftLabels = {!! json_encode($financialTrend['labels']) !!};
        const revData = {!! json_encode($financialTrend['revenue']) !!};
        const expData = {!! json_encode($financialTrend['expenses']) !!};

        new Chart(ftCtx, {
            type: 'bar',
            data: {
                labels: ftLabels,
                datasets: [
                    {
                        label: 'Revenue ($)',
                        data: revData,
                        backgroundColor: '#198754',
                    },
                    {
                        label: 'Inventory Expenses ($)',
                        data: expData,
                        backgroundColor: '#dc3545',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Invoice Pie Chart
        const invCtx = document.getElementById('invoiceChart').getContext('2d');
        const invLabels = {!! json_encode($invoiceChart['labels']) !!};
        const invData = {!! json_encode($invoiceChart['data']) !!};

        new Chart(invCtx, {
            type: 'pie',
            data: {
                labels: invLabels,
                datasets: [{
                    data: invData,
                    backgroundColor: ['#ffc107', '#198754', '#dc3545', '#0dcaf0'],
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
