@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="container py-5">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Verification Reports</h1>
        <div>
            <a href="{{ route('officer.queue') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm me-2">
                <i class="fas fa-list fa-sm text-white-50 me-1"></i> Review Queue
            </a>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm" id="printReport">
                <i class="fas fa-print fa-sm text-white-50 me-1"></i> Print Report
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Registration Statistics Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Registrations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_registrations'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Registrations Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Registrations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_registrations'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verified Registrations Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Verified Registrations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['verified_registrations'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected Registrations Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Rejected Registrations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['rejected_registrations'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Monthly Trends Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Registration Trends</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">View Options:</div>
                            <a class="dropdown-item" href="#" id="viewLast3Months">Last 3 Months</a>
                            <a class="dropdown-item" href="#" id="viewLast6Months">Last 6 Months</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" id="downloadChartPng">Download Chart</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlyTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Status Pie Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Registration Status</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Chart Options:</div>
                            <a class="dropdown-item" href="#" id="toggleLegend">Toggle Legend</a>
                            <a class="dropdown-item" href="#" id="downloadPieChartPng">Download Chart</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="registrationStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="me-2">
                            <i class="fas fa-circle text-warning"></i> Pending
                        </span>
                        <span class="me-2">
                            <i class="fas fa-circle text-success"></i> Verified
                        </span>
                        <span class="me-2">
                            <i class="fas fa-circle text-danger"></i> Rejected
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Officer Performance Table -->
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Officer Performance</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="officerStatsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Officer Name</th>
                                    <th>Total Reviews</th>
                                    <th>Approved</th>
                                    <th>Rejected</th>
                                    <th>Additional Info</th>
                                    <th>Approval Rate</th>
                                    <th>Efficiency</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($officerStats as $stat)
                                <tr>
                                    <td>{{ $stat->officer->name }}</td>
                                    <td>{{ $stat->total_reviews }}</td>
                                    <td>{{ $stat->approved_count }}</td>
                                    <td>{{ $stat->rejected_count }}</td>
                                    <td>{{ $stat->pending_info_count }}</td>
                                    <td>
                                        @if($stat->total_reviews > 0)
                                            {{ number_format(($stat->approved_count / $stat->total_reviews) * 100, 1) }}%
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            @php
                                                $efficiencyScore = $stat->total_reviews > 0 ? min(100, ($stat->total_reviews / max(1, $officerStats->max('total_reviews'))) * 100) : 0;
                                                $colorClass = $efficiencyScore >= 75 ? 'bg-success' : ($efficiencyScore >= 40 ? 'bg-info' : 'bg-warning');
                                            @endphp
                                            <div class="progress-bar {{ $colorClass }}" role="progressbar" 
                                                 style="width: {{ $efficiencyScore }}%;" 
                                                 aria-valuenow="{{ $efficiencyScore }}" aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                {{ number_format($efficiencyScore, 0) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart.js Global Configuration
    Chart.defaults.font.family = "'Open Sans', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
    Chart.defaults.color = '#858796';
    
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Trends Chart
        const monthlyLabels = @json(array_keys($monthlyTrends));
        const monthlyData = @json(array_values($monthlyTrends));
        
        const monthlyCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
        const monthlyTrendsChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'New Registrations',
                    data: monthlyData,
                    backgroundColor: 'rgba(0, 162, 165, 0.1)',
                    borderColor: 'rgba(0, 162, 165, 1)',
                    pointBackgroundColor: 'rgba(0, 162, 165, 1)',
                    pointBorderColor: '#fff',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    },
                    y: {
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value, index, values) {
                                return value;
                            }
                        },
                        grid: {
                            color: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    },
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        titleMarginBottom: 10,
                        titleColor: '#6e707e',
                        titleFont: {
                            size: 14
                        },
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        padding: 15,
                        displayColors: false,
                        caretPadding: 10,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });

        // Registration Status Pie Chart
        const pieCtx = document.getElementById('registrationStatusChart').getContext('2d');
        const registrationStatusChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Verified', 'Rejected'],
                datasets: [{
                    data: [
                        {{ $stats['pending_registrations'] }},
                        {{ $stats['verified_registrations'] }},
                        {{ $stats['rejected_registrations'] }}
                    ],
                    backgroundColor: ['#f6c23e', '#1cc88a', '#e74a3b'],
                    hoverBackgroundColor: ['#e0b135', '#17a673', '#d52a1a'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        displayColors: false,
                        caretPadding: 10,
                    }
                }
            },
        });

        // Event handlers for chart controls
        document.getElementById('viewLast3Months').addEventListener('click', function(e) {
            e.preventDefault();
            updateChartData(monthlyTrendsChart, 3);
        });
        
        document.getElementById('viewLast6Months').addEventListener('click', function(e) {
            e.preventDefault();
            updateChartData(monthlyTrendsChart, 6);
        });
        
        document.getElementById('toggleLegend').addEventListener('click', function(e) {
            e.preventDefault();
            registrationStatusChart.options.plugins.legend.display = !registrationStatusChart.options.plugins.legend.display;
            registrationStatusChart.update();
        });
        
        document.getElementById('downloadChartPng').addEventListener('click', function(e) {
            e.preventDefault();
            downloadChartAsImage('monthlyTrendsChart', 'monthly-trends.png');
        });
        
        document.getElementById('downloadPieChartPng').addEventListener('click', function(e) {
            e.preventDefault();
            downloadChartAsImage('registrationStatusChart', 'registration-status.png');
        });
        
        document.getElementById('printReport').addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
        
        // Helper function to update chart data based on months
        function updateChartData(chart, months) {
            if (monthlyLabels.length <= months) return;
            
            const newLabels = monthlyLabels.slice(-months);
            const newData = monthlyData.slice(-months);
            
            chart.data.labels = newLabels;
            chart.data.datasets[0].data = newData;
            chart.update();
        }
        
        // Helper function to download chart as image
        function downloadChartAsImage(chartId, filename) {
            const canvas = document.getElementById(chartId);
            const image = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.download = filename;
            link.href = image;
            link.click();
        }
    });
</script>
@endpush

<style>
/* Print styles */
@media print {
    .navbar, .footer, .dropdown-toggle, .dropdown-menu, .no-print {
        display: none !important;
    }
    
    .card {
        break-inside: avoid;
    }
    
    .container {
        width: 100%;
        max-width: 100%;
    }
    
    .chart-area, .chart-pie {
        height: 300px !important;
    }
}

/* Custom styles for reports page */
.border-left-primary {
    border-left: 4px solid var(--fnbb-primary);
}
.border-left-success {
    border-left: 4px solid var(--fnbb-success);
}
.border-left-warning {
    border-left: 4px solid var(--fnbb-warning);
}
.border-left-danger {
    border-left: 4px solid var(--fnbb-danger);
}

.chart-area {
    position: relative;
    height: 320px;
    width: 100%;
}

.chart-pie {
    position: relative;
    height: 300px;
    width: 100%;
}
</style>
@endsection