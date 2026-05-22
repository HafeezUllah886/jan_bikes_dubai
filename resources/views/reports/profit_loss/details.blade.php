@extends('layout.popups')
@section('content')
        <div class="row justify-content-center">
            <div class="col-xxl-12">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                                <a href="javascript:window.print()" class="btn btn-success ml-4"><i class="ri-printer-line mr-4"></i> Print</a>
                            </div>
                            <div class="card-header border-bottom-dashed p-4">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h1>{{ projectNameHeader() }}</h1>
                                    </div>
                                    <div class="flex-shrink-0 mt-sm-0 mt-3">
                                        <h3>Profit Loss Report</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Dates</p>
                                        <h5 class="fs-14 mb-0"><small class="text-muted" id="invoice-time">From </small><span id="invoice-date">{{ date("d M Y", strtotime($from)) }}</span> </h5>
                                        <h5 class="fs-14 mb-0"><small class="text-muted" id="invoice-time">To &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</small><span id="invoice-date">{{ date("d M Y", strtotime($to)) }}</span> </h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Products</p>
                                        <h5 class="fs-14 mb-0"><span id="total-products">{{ number_format($purchases->count()) }}</span></h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Printed On</p>
                                        <h5 class="fs-14 mb-0"><span id="total-amount">{{ date("d M Y") }}</span></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="table-responsive">
                                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col" style="width: 50px;">#</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Chassis No</th>
                                                <th scope="col" class="text-start">Vehicle</th>
                                                <th scope="col" class="text-end">Purchase Price</th>
                                                <th scope="col" class="text-end">Initial Expense</th>
                                                <th scope="col" class="text-end">Adj. Expense</th>
                                                <th scope="col" class="text-end">Adj. Profit</th>
                                                <th scope="col" class="text-end">Actual Cost</th>
                                                <th scope="col" class="text-end">Sale Price</th>
                                                <th scope="col" class="text-end">Net Profit/Loss</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products-list">
                                        @foreach ($purchases as $key => $purchase)
                                            @php
                                                $expenseAdjustments = $purchase->expenseProfits->where('type', 'expense')->sum('amount');
                                                $profitAdjustments = $purchase->expenseProfits->where('type', 'profit')->sum('amount');
                                                $actualCost = $purchase->total + $expenseAdjustments - $profitAdjustments;
                                                $salePrice = optional($purchase->saleCar)->total ?? $purchase->sale_price;
                                                $netProfitLoss = $salePrice - $actualCost;
                                            @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $purchase->date ? date('d M Y', strtotime($purchase->date)) : '-' }}</td>
                                                <td>{{ $purchase->chassis }}</td>
                                                <td class="text-start" style="max-width: 220px; overflow-wrap: break-word; white-space: normal;">
                                                    {{ trim($purchase->company.' '.$purchase->model.' '.$purchase->color) }}
                                                </td>
                                                <td class="text-end">{{ number_format($purchase->price, 2) }}</td>
                                                <td class="text-end">{{ number_format($purchase->expense, 2) }}</td>
                                                <td class="text-end">{{ number_format($expenseAdjustments, 2) }}</td>
                                                <td class="text-end">{{ number_format($profitAdjustments, 2) }}</td>
                                                <td class="text-end">{{ number_format($actualCost, 2) }}</td>
                                                <td class="text-end">{{ number_format($salePrice, 2) }}</td>
                                                <td class="text-end {{ $netProfitLoss < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($netProfitLoss, 2) }}</td>
                                                <td>{{ $purchase->saleCar ? 'Sold' : 'Expected' }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            @php
                                                $totalExpenseAdjustments = $purchases->sum(fn ($purchase) => $purchase->expenseProfits->where('type', 'expense')->sum('amount'));
                                                $totalProfitAdjustments = $purchases->sum(fn ($purchase) => $purchase->expenseProfits->where('type', 'profit')->sum('amount'));
                                                $totalActualCost = $purchases->sum(fn ($purchase) => $purchase->total + $purchase->expenseProfits->where('type', 'expense')->sum('amount') - $purchase->expenseProfits->where('type', 'profit')->sum('amount'));
                                                $totalSalePrice = $purchases->sum(fn ($purchase) => optional($purchase->saleCar)->total ?? $purchase->sale_price);
                                                $totalNetProfitLoss = $totalSalePrice - $totalActualCost;
                                            @endphp
                                            <tr>
                                                <th colspan="4" class="text-end p-1 m-0">Total</th>
                                                <th class="text-end p-1 m-0">{{ number_format($purchases->sum('price'), 2) }}</th>
                                                <th class="text-end p-1 m-0">{{ number_format($purchases->sum('expense'), 2) }}</th>
                                                <th class="text-end p-1 m-0">{{ number_format($totalExpenseAdjustments, 2) }}</th>
                                                <th class="text-end p-1 m-0">{{ number_format($totalProfitAdjustments, 2) }}</th>
                                                <th class="text-end p-1 m-0">{{ number_format($totalActualCost, 2) }}</th>
                                                <th class="text-end p-1 m-0">{{ number_format($totalSalePrice, 2) }}</th>
                                                <th class="text-end p-1 m-0 {{ $totalNetProfitLoss < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($totalNetProfitLoss, 2) }}</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/libs/datatable/datatable.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/libs/datatable/responsive.bootstrap.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/libs/datatable/buttons.dataTables.min.css') }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatable/jszip.min.js') }}"></script>

    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
@endsection
