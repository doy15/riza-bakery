@extends('layouts.main', ['title' => 'Dashboard'])

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            @if ($userRole === 'admin' || $userRole === 'gudang')
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card card-statistic-2">
                            <div class="card-icon shadow-primary bg-info">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Raw</h4>
                                </div>
                                <div class="card-body" id="countRaw">
                                    {{ $countRaw }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-statistic-2">
                            <div class="card-icon shadow-primary bg-success">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Finish Good</h4>
                                </div>
                                <div class="card-body" id="countFinishGood">
                                    {{ $countFinishGood }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12 ">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Material</h4>

                                <div class="form-inline">
                                    <label for="filterType" class="mr-2 mb-0">Type:</label>
                                    <select id="filterType" class="form-control form-control-sm">
                                        <option value="">All</option>
                                        <option value="raw">Raw</option>
                                        <option value="finish_good">Finish Good</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card-body">
                                <canvas id="materialChart" height="450"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($userRole === 'admin' || $userRole === 'gudang')
                <div class="row">
                    <div class="col-lg-12 ">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Material Stock</h4>

                                {{-- <div class="form-inline">
                                    <label for="filterType" class="mr-2 mb-0">Type:</label>
                                    <select id="filterType" class="form-control form-control-sm">
                                        <option value="">All</option>
                                        <option value="raw">Raw</option>
                                        <option value="finish_good">Finish Good</option>
                                    </select>
                                </div> --}}
                            </div>

                            <div class="card-body">
                                <canvas id="materialStockChart" height="450"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Material Stock List</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped text-center align-middle"
                                        id="materialStockTable" style="width: 100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th>Material Name</th>
                                                <th>Quantity</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                @if ($userRole === 'admin')
                                                    <th>Action</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Data akan diisi oleh DataTables --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($userRole === 'admin' || $userRole === 'produksi')
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Efficiency</h4>
                            </div>
                            <div class="card-body">
                                <div class="row" id="efficiencyCards"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($userRole === 'admin' || $userRole === 'qc')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Quality Inspections</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped text-center align-middle"
                                        id="inspectionTable" style="width: 100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>User ID</th>
                                                <th>Production Data ID</th>
                                                <th>Judgement</th>
                                                <th>Corrective Action</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                @if ($userRole === 'admin')
                                                    <th>Action</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- DataTables will populate this --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </section>
    </div>
@endsection
<!-- DataTables Styles -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>

<!-- jQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const columnsInspection = [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                className: 'text-center align-middle',
                orderable: false,
                searchable: false
            },
            {
                data: 'user.name',
                name: 'user.name',
                className: 'text-left align-middle'
            },
            {
                data: 'production_data_id',
                name: 'production_data_id',
                className: 'text-left align-middle'
            },
            {
                data: 'judgement',
                name: 'judgement',
                className: 'text-left align-middle'
            },
            {
                data: 'corrective_action',
                name: 'corrective_action',
                className: 'text-left align-middle'
            },
            {
                data: 'created_at',
                name: 'created_at',
                className: 'text-left align-middle'
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                className: 'text-left align-middle'
            }
        ];

        @if ($userRole === 'admin')
            columnsInspection.push({
                data: null,
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'text-center align-middle',
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-danger btn-sm btn-deletes"
                                data-id="${row.id}"
                                data-name="Inspection #${row.id}">
                            Delete
                        </button>
                    `;
                }
            });
        @endif

    const tableInspection = new DataTable('#inspectionTable', {
            processing: false,
            serverSide: true,
            ajax: "{{ route('qualityinspections.data') }}",
            columns: columnsInspection
        });

        // Auto reload setiap 5 detik
        setInterval(() => {
            tableInspection.ajax.reload(null, false);
        }, 5000);

        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('btn-deletes')) {
                const id = e.target.dataset.id;
                const name = e.target.dataset.name;

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: `Data akan dihapus permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/quality-inspections/destroy/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                            .then(res => res.json())
                            .then(response => {
                                if (response.success) {
                                    Swal.fire('Berhasil!', response.message, 'success');
                                    tableInspection.ajax.reload();
                                } else {
                                    Swal.fire('Gagal!', response.message, 'error');
                                }
                            });
                    }
                });
            }
        });
        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('btn-delete')) {
                const id = e.target.dataset.id;
                const name = e.target.dataset.name;

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: `Data untuk "${name}" akan dihapus permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/materialstock/destroy/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        }).then(res => res.json())
                            .then(response => {
                                if (response.success) {
                                    Swal.fire('Berhasil!', response.message, 'success');
                                    tableStock.ajax.reload();
                                } else {
                                    Swal.fire('Gagal!', response.message, 'error');
                                }
                            });
                    }
                });
            }
        });

        const ctx = document.getElementById('materialChart');
        if (!ctx) {
            console.error('Canvas dengan id #materialChart tidak ditemukan.');
            return;
        }

        const materialChart = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Stock',
                    data: [],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Minimum Stock',
                    data: [],
                    type: 'scatter',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 1)',
                    showLine: false,
                    pointRadius: 5
                }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        type: 'category',
                        labels: [],
                        ticks: {
                            autoSkip: false
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 20,
                            min: 0,
                            max: 100 // default, akan diubah dinamis
                        }
                    }]
                }
            }
        });

        let selectedType = '';

        function fetchMaterialData() {
            const url = "{{ route('dashboard.material-data') }}?type=" + selectedType;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const labels = data.labels || [];
                    const stocks = (data.stocks || []).map(v => Number(v) || 0);
                    const minStocks = (data.minStocks || []).map(v => Number(v) || 0);

                    if (labels.length !== stocks.length || labels.length !== minStocks.length) {
                        console.error("Data tidak valid: jumlah labels, stocks, dan minStocks tidak sama.");
                        return;
                    }

                    // Update chart
                    const scatterMinStocks = labels.map((label, index) => ({
                        x: label,
                        y: minStocks[index]
                    }));

                    const maxVal = Math.max(...stocks, ...minStocks);
                    const dynamicMax = Math.ceil(maxVal + 10);

                    materialChart.data.labels = labels;
                    materialChart.data.datasets[0].data = stocks;
                    materialChart.data.datasets[1].data = scatterMinStocks;
                    materialChart.options.scales.xAxes[0].labels = labels;
                    materialChart.options.scales.yAxes[0].ticks.max = dynamicMax;
                    materialChart.update();

                    // ✅ Update total stock secara realtime
                    document.getElementById('countRaw').innerText = data.countRaw;
                    document.getElementById('countFinishGood').innerText = data.countFinishGood;
                })
                .catch(err => console.error("Fetch error: ", err));
        }

        const currentUserRole = "{{ $userRole }}";
        document.getElementById('filterType').addEventListener('change', function () {
            selectedType = this.value;
            fetchMaterialData();
        });

        fetchMaterialData();
        setInterval(fetchMaterialData, 5000); // refresh tiap 5 detik

        const stockCtx = document.getElementById('materialStockChart');
        if (!stockCtx) {
            console.error('Canvas dengan id #materialStockChart tidak ditemukan.');
            return;
        }

        const materialStockChart = new Chart(stockCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Stock Quantity',
                    data: [],
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        type: 'category',
                        labels: [],
                        ticks: {
                            autoSkip: false
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 20,
                            min: 0,
                            max: 100 // default, diubah dinamis nanti
                        }
                    }]
                }
            }
        });

        function fetchMaterialStockData() {
            fetch("{{ route('dashboard.getMaterialStockData') }}")
                .then(response => response.json())
                .then(data => {
                    const labels = data.labels || [];
                    const stocks = (data.stocks || []).map(v => Number(v) || 0);

                    const maxVal = Math.max(...stocks);
                    const dynamicMax = Math.ceil(maxVal + 10);

                    materialStockChart.data.labels = labels;
                    materialStockChart.data.datasets[0].data = stocks;
                    materialStockChart.options.scales.xAxes[0].labels = labels;
                    materialStockChart.options.scales.yAxes[0].ticks.max = dynamicMax;

                    materialStockChart.update();
                })
                .catch(err => console.error("Fetch error: ", err));
        }

        fetchMaterialStockData();
        setInterval(fetchMaterialStockData, 5000); // refresh tiap 5 detik

        const tableStock = new DataTable('#materialStockTable', {
            processing: false,
            serverSide: true,
            ajax: "{{ route('materialstock.data') }}",
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                className: 'text-center align-middle',
                orderable: false,
                searchable: false
            },
            {
                data: 'material.material_name',
                name: 'material.material_name',
                className: 'text-left align-middle'
            },
            {
                data: 'qty',
                name: 'qty',
                className: 'text-left align-middle'
            },
            {
                data: 'status',
                name: 'status',
                className: 'text-left align-middle'
            },
            {
                data: 'created_at',
                name: 'created_at',
                className: 'text-left align-middle'
            },
                @if ($userRole === 'admin')
                                                                                                                            {
                        data: null,
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center align-middle',
                        render: function (data, type, row) {
                            return `
                                                                                                                            <button class="btn btn-danger btn-sm btn-delete"
                                                                                                                                    data-id="${row.id}"
                                                                                                                                    data-name="${row.material?.material_name ?? ''}">
                                                                                                                                Delete
                                                                                                                            </button>
                                                                                                                        `;
                        }
                    }
                @endif
            ]
        });

        // Auto reload data setiap 5 detik
        setInterval(() => {
            tableStock.ajax.reload(null, false); // false agar tidak reset halaman
        }, 5000);

    });

    function loadEfficiencyCharts() {
        fetch('/dashboard/efficiency-data')
            .then(res => res.json())
            .then(lines => {
                const container = document.getElementById('efficiencyCards');
                if (!container) return;
                container.innerHTML = '';

                lines.forEach(line => {
                    const cardId = `chart-${line.line_name}`;

                    const card = `
                    <div class="col-lg-12 mb-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5>${line.line_name}</h5>
                            </div>
                            <div class="card-body">
                                <div id="${cardId}" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                `;

                    container.insertAdjacentHTML('beforeend', card);

                    Highcharts.chart(cardId, {
                        chart: {
                            type: 'line',
                            backgroundColor: '#1e2a3a',
                            style: {
                                fontFamily: 'Arial'
                            }
                        },
                        title: {
                            text: `Actual vs Plan`,
                            style: {
                                color: '#fff'
                            }
                        },
                        xAxis: {
                            categories: line.hours,
                            labels: {
                                style: {
                                    color: '#ccc'
                                }
                            },
                            title: {
                                text: 'Hour of the Day',
                                style: {
                                    color: '#ccc'
                                }
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Quantity',
                                style: {
                                    color: '#ccc'
                                }
                            },
                            labels: {
                                style: {
                                    color: '#ccc'
                                }
                            }
                        },
                        tooltip: {
                            shared: true
                        },
                        series: [{
                            name: 'OK',
                            data: line.ok_values,
                            color: '#00BFFF'
                        },
                        {
                            name: 'Plan Qty',
                            data: line.plan_values,
                            color: '#FF69B4',
                            dashStyle: 'ShortDash'
                        }
                        ],
                        legend: {
                            itemStyle: {
                                color: '#fff'
                            }
                        }
                    });
                });
            });
    }

    loadEfficiencyCharts();
    setInterval(loadEfficiencyCharts, 5000);



</script>