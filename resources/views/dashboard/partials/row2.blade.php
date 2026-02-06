@if(Auth::user()->is_technician)
<!-- 2nd Row: Charts -->
<div class="row mb-4">
    <!-- Line Chart -->
    <div class="col-lg-8 mb-4 mb-lg-0">
        <div class="card shadow-sm rounded-3 p-3 bg-white">

            <h5 class="card-title text-primary fw-semibold mb-3">Total Repairs per Month 📈</h5>
            <div class="p-3" style="height: 350px;"> <!-- Added padding and min-height for chart container -->
                <canvas id="lineChart"></canvas>
            </div>
        </div>
    </div>
    <!-- Doughnut Chart -->
    <div class="col-lg-4">
        <div class="card shadow-sm rounded-3 p-3 bg-white">
            <h5 class="card-title text-primary fw-semibold mb-3">Repair Breakdown 📊 | Total: {{$repairs_count}}</h5>
            <div class="p-3 d-flex justify-content-center align-items-center" style="height: 350px;"> <!-- Added padding and min-height -->
                <canvas id="doughnutChart"></canvas>
            </div>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ... (other chart initializations and code)

        // Initialize Line Chart
        const lineCtx = document.getElementById('lineChart').getContext('2d');

        // Pass the PHP array to a JavaScript variable as a JSON string
        const lineChartDataString = `{!! json_encode($line_chart_data) !!}`;

        // Parse the JSON string into a JavaScript array
        const lineChartData = JSON.parse(lineChartDataString);

        new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Total Repairs',
                    data: lineChartData,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    title: {
                        display: false,
                        text: 'Total Repairs per Month'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Initialize Doughnut Chart
        const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
        new Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed Repairs', 'Ongoing Repairs', 'Unclaimed Repairs', 'Cancelled Repairs'],
                datasets: [{
                    label: 'Repair Breakdown',
                    data: [
                        parseInt('{{$completed_repairs_count}}'),
                        parseInt('{{$ongoing_repairs_count}}'),
                        parseInt('{{$unclaimed_repairs_count}}'),
                        parseInt('{{$cancelled_repairs_count}}')
                    ], // Make sure data is numeric
                    backgroundColor: [
                        'rgba(76, 175, 80, 0.7)', // Green for Completed
                        'rgba(0, 188, 212, 0.7)', // Cyan for Ongoing
                        'rgba(255, 152, 0, 0.7)', // Orange for Unclaimed
                        'rgba(244, 67, 54, 0.7)' // Red for Cancelled
                    ],
                    borderColor: [
                        'rgba(76, 175, 80, 1)',
                        'rgba(0, 188, 212, 1)',
                        'rgba(255, 152, 0, 1)',
                        'rgba(244, 67, 54, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false,
                        text: 'Repair Breakdown'
                    },
                    datalabels: {
                        color: '#fff',
                        formatter: (value, context) => {
                            return value; // show the raw value (you can customize to show % or label too)
                        },
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        anchor: 'center',
                        align: 'center'
                    }
                }
            },
            plugins: [ChartDataLabels] // Enable the datalabels plugin here
        });
    });
</script>
@endif