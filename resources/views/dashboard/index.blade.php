@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card crm-widget">
                <div class="card-body p-0">
                    <div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
                        @can('Business Balance')
                            <div class="col">
                                <div class="py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Business Accounts</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0 cfs-22"><span class="counter-value"
                                                    data-target="{{ accountBalanceByType('Business') }}">0</span></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @can('Customer Balance')
                            <div class="col">
                                <div class="mt-3 mt-md-0 py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Customer Accounts</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0 cfs-22"><span class="counter-value"
                                                    data-target="{{ accountBalanceByType('Customer') }}">0</span></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @can('Vendor Balance')
                            <div class="col">
                                <div class="mt-3 mt-md-0 py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Vendor Accounts</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0 cfs-22"><span class="counter-value"
                                                    data-target="{{ accountBalanceByType('Vendor') }}">0</span></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @can('Available Purchases')
                            <div class="col">
                                <div class="mt-3 mt-lg-0 py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Available Purchases</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0 cfs-22"><span class="counter-value"
                                                    data-target="{{ totalAvailablePurchasesAmount() }}">0</span></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @can('Booked Advance')
                            <div class="col">
                                <div class="mt-3 mt-lg-0 py-4 px-3">
                                    <h5 class="text-muted text-uppercase fs-13">Booked Advance</h5>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 ms-3">
                                            <h2 class="mb-0 cfs-22"><span class="counter-value"
                                                    data-target="{{ totalAdvanceBooked() }}">0</span></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    @can('Monthly Sales')
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0 align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Revenue (Monthly)</h4>
                    </div>
                    <div class="card-header p-0 border-0 bg-light-subtle">
                        <div class="row g-0 text-center">
                            <div class="col-6 col-sm-3">
                                <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-1"><span class="counter-value" data-target="{{ $last_sale }}">0</span>
                                    </h5>
                                    <p class="text-muted mb-0">Sales</p>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-1"><span class="counter-value" data-target="{{ $last_expense }}">0</span>
                                    </h5>
                                    <p class="text-muted mb-0">Expenses</p>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="p-3 border border-dashed border-start-0">
                                    <h5 class="mb-1"><span class="counter-value" data-target="{{ $last_profit }}">0</span>
                                    </h5>
                                    <p class="text-muted mb-0">Profit</p>
                                </div>
                            </div>
                            <div class="col-6 col-sm-3">
                                <div class="p-3 border border-dashed border-start-0 border-end-0">
                                    <h5 class="mb-1 text-success"><span class="counter-value"
                                            data-target="{{ $last_profit - $last_expense }}">0</span></h5>
                                    <p class="text-muted mb-0">Net Profit</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0 pb-2">
                        <div class="w-100">
                            <div id="customer_impression_charts" data-colors='["--vz-primary", "--vz-success", "--vz-danger"]'
                                data-colors-minimal='["--vz-light", "--vz-primary", "--vz-info"]'
                                data-colors-saas='["--vz-success", "--vz-info", "--vz-danger"]'
                                data-colors-modern='["--vz-warning", "--vz-primary", "--vz-success"]'
                                data-colors-interactive='["--vz-info", "--vz-primary", "--vz-danger"]'
                                data-colors-creative='["--vz-warning", "--vz-primary", "--vz-danger"]'
                                data-colors-corporate='["--vz-light", "--vz-primary", "--vz-secondary"]'
                                data-colors-galaxy='["--vz-secondary", "--vz-primary", "--vz-primary-rgb, 0.50"]'
                                data-colors-classic='["--vz-light", "--vz-primary", "--vz-secondary"]'
                                data-colors-vintage='["--vz-success", "--vz-primary", "--vz-secondary"]' class="apex-charts"
                                dir="ltr"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    </div>
@endsection
@section('page-css')
@endsection
@section('page-js')
    <script>
        window.monthlySales = @json($monthlySales);
        window.monthlyExpenses = @json($monthlyExpenses);
        window.monthlyProfit = @json($monthlyProfit);
    </script>
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/dashboard-ecommerce.init.js') }}"></script>
@endsection
