@extends('admin.frontend.partials.app')

@section('content')

<!-- Body Wrapper -->
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
    data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

    <div class="body-wrapper">
        @include('admin.frontend.partials.header')

        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <h5 class="card-title fw-semibold mb-4">Admin Dashboard</h5>

                    <!-- Dashboard Cards -->
                    <div class="row g-4">
                        <!-- Total Revenue -->
                        <div class="col-md-4 col-sm-6">
                            <div class="card shadow-sm border-0" style="background: #FF9066;">
                                <div class="card-body text-white position-relative">
                                    <h5 class="fw-bold">{{ number_format($totalRevenue, 2) }}</h5>
                                    <small class="d-block mb-2">All Revenue</small>
                                    <img src="https://static.vecteezy.com/system/resources/previews/029/920/687/non_2x/revenue-growth-increasing-graph-high-interest-rate-stock-illustration-vector.jpg" alt="chart"
                                         class="position-absolute" style="width: 50px; bottom: 15px; right: 15px;">
                                    <div class="mt-3"><small><i class="bi bi-clock"></i> update : {{ now()->format('g:i a') }}</small></div>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Completed Orders -->
                        <div class="col-md-4 col-sm-6">
                            <div class="card shadow-sm border-0" style="background: #00D27A;">
                                <div class="card-body text-white position-relative">
                                    <h5 class="fw-bold">{{ $monthlyOrders }}+</h5>
                                    <small class="d-block mb-2">Monthly Completed Orders</small>
                                    <img src="{{ asset('https://static.vecteezy.com/system/resources/thumbnails/016/139/543/small_2x/growth-arrow-icon-in-flat-style-revenue-illustration-on-white-isolated-background-increase-business-concept-vector.jpg') }}" alt="chart"
                                         class="position-absolute" style="width: 50px; bottom: 15px; right: 15px;">
                                    <div class="mt-3"><small><i class="bi bi-clock"></i> update : {{ now()->format('g:i a') }}</small></div>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Revenue -->
                        <div class="col-md-4 col-sm-6">
                            <div class="card shadow-sm border-0" style="background: #FF5F77;">
                                <div class="card-body text-white position-relative">
                                    <h5 class="fw-bold">{{ number_format($completedRevenue, 2) }}</h5>
                                    <small class="d-block mb-2">Monthly Completed Revenue</small>
                                    <img src="https://thumbs.dreamstime.com/b/vector-completed-stamp-isolated-white-31258902.jpg" alt="chart"
                                         class="position-absolute" style="width: 50px; bottom: 15px; right: 15px;">
                                    <div class="mt-3"><small><i class="bi bi-clock"></i> update : {{ now()->format('g:i a') }}</small></div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Revenue -->
                        <div class="col-md-4 col-sm-6">
                            <div class="card shadow-sm border-0" style="background: #00B8D9;">
                                <div class="card-body text-white position-relative">
                                    <h5 class="fw-bold">{{ number_format($pendingRevenue, 2) }}</h5>
                                    <small class="d-block mb-2">Pending Revenue</small>
                                    <img src="https://c8.alamy.com/comp/2JHPF9R/pending-text-written-on-yellow-black-round-stamp-sign-2JHPF9R.jpg" alt="chart"
                                         class="position-absolute" style="width: 50px; bottom: 15px; right: 15px;">
                                    <div class="mt-3"><small><i class="bi bi-clock"></i> update : {{ now()->format('g:i a') }}</small></div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Orders -->
                        <div class="col-md-4 col-sm-6">
                            <div class="card shadow-sm border-0" style="background: #A66DD4;">
                                <div class="card-body text-white position-relative">
                                    <h5 class="fw-bold">{{ $totalOrders }}</h5>
                                    <small class="d-block mb-2">Total Orders</small>
                                    <img src="https://static.thenounproject.com/png/890166-200.png" alt="chart"
                                         class="position-absolute" style="width: 50px; bottom: 15px; right: 15px;">
                                    <div class="mt-3"><small><i class="bi bi-clock"></i> update : {{ now()->format('g:i a') }}</small></div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending & Cancelled Orders -->
                        <div class="col-md-4 col-sm-6">
                            <div class="card shadow-sm border-0" style="background: #FFA500;">
                                <div class="card-body text-white position-relative">
                                    <h5 class="fw-bold">Pending: {{ $pendingOrders }} | Cancelled: {{ $cancelledOrders }}</h5>
                                    <small class="d-block mb-2">Pending & Cancelled Orders</small>
                                    <img src="https://www.shutterstock.com/image-vector/order-cancelled-thin-line-icon-260nw-2275150223.jpg" alt="chart"
                                         class="position-absolute" style="width: 50px; bottom: 15px; right: 15px;">
                                    <div class="mt-3"><small><i class="bi bi-clock"></i> update : {{ now()->format('g:i a') }}</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
<div class="container py-4">
    <h4 class="mb-4">Revenue Analytics</h4>

    <div class="mb-3 d-flex justify-content-end">
        <select id="granularity" class="form-select w-auto">
            <option value="monthly" selected>Monthly</option>
            <option value="yearly">Yearly</option>
            <option value="daily">Daily</option>
        </select>
    </div>

    <div class="chart-container" style="position: relative; height:400px; width:100%">
        <canvas id="revenueChart"></canvas>
    </div>
</div>
                    <!-- Footer -->
                    <div class="py-6 px-6 text-center mt-5">
                        <p class="mb-0 fs-4">
                            Design and Developed by 
                            <a href="https://adminmart.com/" target="_blank" class="pe-1 text-white text-decoration-underline">Elixore.com</a> 
                            Distributed by 
                            <a target="_blank" class="pe-1 text-white text-decoration-underline">Elixore</a>
                        </p>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>


@endsection
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Then load Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let revenueChart;
    const granularitySelect = document.getElementById('granularity');
    const chartCanvas = document.getElementById('revenueChart');
    
    // Check if elements exist
    if (!granularitySelect || !chartCanvas) {
        console.error('Required elements not found');
        return;
    }

    function fetchRevenueData(granularity = 'monthly') {
        fetch(`/admin/revenue-data?granularity=${granularity}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        })
        .then(data => {
            console.log('Received data:', data); // Debug log
            if (!data.labels || !data.data || data.labels.length === 0 || data.data.length === 0) {
                showNoDataMessage();
            } else {
                updateChart(data.labels, data.data);
            }
        })
        .catch(error => {
            console.error('Error fetching revenue data:', error);
            showNoDataMessage();
        });
    }

    function updateChart(labels, data) {
        if (revenueChart) revenueChart.destroy();
        
        // Remove any existing "no data" message
        const noDataMessage = document.getElementById('noDataMessage');
        if (noDataMessage) noDataMessage.remove();

        const ctx = chartCanvas.getContext('2d');
        revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue (Delivered Orders)',
                    data: data,
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3,
                    borderColor: '#4A90E2',
                    backgroundColor: '#4A90E2',
                    pointBackgroundColor: '#4A90E2'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Revenue (PKR)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Time Period'
                        }
                    }
                }
            }
        });
    }

    function showNoDataMessage() {
        if (revenueChart) {
            revenueChart.destroy();
            revenueChart = null;
        }

        if (!document.getElementById('noDataMessage')) {
            const message = document.createElement('div');
            message.id = 'noDataMessage';
            message.className = 'alert alert-info text-center';
            message.style.width = '100%';
            message.style.padding = '20px';
            message.style.margin = '20px auto';
            message.textContent = 'No revenue data available for the selected period.';
            
            chartCanvas.parentNode.insertBefore(message, chartCanvas.nextSibling);
        }
    }

    // Event listeners
    granularitySelect.addEventListener('change', function() {
        fetchRevenueData(this.value);
    });

    // Initial load
    fetchRevenueData();
});
</script>