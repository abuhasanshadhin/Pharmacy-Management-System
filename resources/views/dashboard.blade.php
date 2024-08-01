@extends('layout.app')
@section('content')
    <div class="pagetitle">
        <h1 class="text-capitalize">{{ translator('Welcome back')  }} {{ auth()->user()->name }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">{{ translator('To Admin Dashboard') }}</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <x-statistic
                title="Total Medicines"
                icon="bi bi-capsule-pill"
                variant="variant-1"
                count="{{ $total_medicine }}"
            ></x-statistic>
            <x-statistic
                title="Total Sales"
                variant="variant-2"
                count="{{ formatPrice($total_sales) }}"
            ></x-statistic>
            <x-statistic
                title="Total Purchase"
                variant="variant-3"
                count="{{ formatPrice($total_purchase) }}"
            ></x-statistic>
            <x-statistic
                title="Total In Stock"
                icon="bi bi-box"
                variant="variant-4"
                count="{{ $total_stock }}"
            ></x-statistic>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header border-0">
                        <h6>{{ translator('Today Reports') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="report-box bg-1">
                                    <h4>{{ formatPrice($today_purchase) }}</h4>
                                    {{ translator('Purchase Today') }}
                                </div>
                            </div>
                            <div class="col">
                                <div class="report-box bg-2">
                                    <h4>{{ formatPrice($today_sale) }}</h4>
                                    {{ translator('Sales Today') }}
                                </div>
                            </div>
                            <div class="col">
                                <div class="report-box bg-3">
                                    <h4>{{ formatPrice($today_profit) }}</h4>
                                    {{ translator('Profit Today') }}
                                </div>
                            </div>
                            <div class="col">
                                <div class="report-box bg-4">
                                    <h4>{{ formatPrice($today_purchase) }}</h4>
                                    {{ translator('Expense Today') }}
                                </div>
                            </div>
                            <div class="col">
                                <div class="report-box bg-5">
                                    <h4>{{ formatPrice($today_sale) }}</h4>
                                    {{ translator('Cash In Today') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9">
                <div class="card card-body">
                    <h6 class="pt-3"><i class="bi bi-bar-chart"></i> {{ translator('Sale, Purchase & Profit Report') }}</h6>
                    <div id="chart"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card card-body">
                    <h6 class="pt-3"><i class="bi bi-pie-chart"></i> {{ translator('Sale & Purchase') }}</h6>
                    <div id="pieChart" class="py-4"></div>
                </div>
            </div>

        </div>
    </section>
@endsection
@push('scripts')
    <script>
        var options = {!! json_encode($donut_chart) !!};

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>


    <script>
        var options = {
            series: [{{ $total_purchase }}, {{ $total_sales }}],
            chart: {
                width: 220,
                type: 'donut',
            },
            colors: ['#752BDF', '#00D89E'],
            labels: ['Purchase', 'Sales'],
            legend: {
                position: 'bottom',
                show: true,
                showForSingleSeries: false,
                showForNullSeries: true,
                showForZeroSeries: true,
            },
        };

        var chart = new ApexCharts(document.querySelector("#pieChart"), options);
        chart.render();
    </script>
@endpush
