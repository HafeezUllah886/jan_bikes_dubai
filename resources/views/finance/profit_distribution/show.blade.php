@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                            <button class="btn btn-success ml-4" onclick="window.print()"><i class="ri-printer-line"></i> Print</button>
                        </div>
                        <div class="card-header border-bottom-dashed p-4">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h1>{{ projectNameHeader() }}</h1>
                                </div>
                                <div class="flex-shrink-0 mt-sm-0 mt-3">
                                    <h3>Profit Loss Distribution</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Distribution Date</p>
                                    <h5 class="fs-14 mb-0"><span id="invoice-date">{{ date('d M Y', strtotime($profit_distribution->date)) }}</span> </h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Ref #</p>
                                    <h5 class="fs-14 mb-0"><span id="invoice-date">{{ $profit_distribution->refID }}</span> </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="card-header">
                                <h3>Vehicals</h3>
                            </div>
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
                                                $expenseAdjustments = $purchase->expenseProfits
                                                    ->where('type', 'expense')
                                                    ->sum('amount');
                                                $profitAdjustments = $purchase->expenseProfits
                                                    ->where('type', 'profit')
                                                    ->sum('amount');
                                                $actualCost =
                                                    $purchase->total + $expenseAdjustments - $profitAdjustments;
                                                $salePrice =
                                                    optional($purchase->saleCar)->total ?? $purchase->sale_price;
                                                $netProfitLoss = $salePrice - $actualCost;
                                            @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $purchase->date ? date('d M Y', strtotime($purchase->date)) : '-' }}
                                                </td>
                                                <td>{{ $purchase->chassis }}</td>
                                                <td class="text-start"
                                                    style="max-width: 220px; overflow-wrap: break-word; white-space: normal;">
                                                    {{ trim($purchase->company . ' ' . $purchase->model . ' ' . $purchase->color) }}
                                                </td>
                                                <td class="text-end">{{ number_format($purchase->price, 2) }}</td>
                                                <td class="text-end">{{ number_format($purchase->expense, 2) }}</td>
                                                <td class="text-end">{{ number_format($expenseAdjustments, 2) }}</td>
                                                <td class="text-end">{{ number_format($profitAdjustments, 2) }}</td>
                                                <td class="text-end">{{ number_format($actualCost, 2) }}</td>
                                                <td class="text-end">{{ number_format($salePrice, 2) }}</td>
                                                <td
                                                    class="text-end {{ $netProfitLoss < 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ number_format($netProfitLoss, 2) }}</td>
                                                <td>{{ $purchase->saleCar ? 'Sold' : 'Expected' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        @php
                                            $totalExpenseAdjustments = $purchases->sum(
                                                fn($purchase) => $purchase->expenseProfits
                                                    ->where('type', 'expense')
                                                    ->sum('amount'),
                                            );
                                            $totalProfitAdjustments = $purchases->sum(
                                                fn($purchase) => $purchase->expenseProfits
                                                    ->where('type', 'profit')
                                                    ->sum('amount'),
                                            );
                                            $totalActualCost = $purchases->sum(
                                                fn($purchase) => $purchase->total +
                                                    $purchase->expenseProfits->where('type', 'expense')->sum('amount') -
                                                    $purchase->expenseProfits->where('type', 'profit')->sum('amount'),
                                            );
                                            $totalSalePrice = $purchases->sum(
                                                fn($purchase) => optional($purchase->saleCar)->total ??
                                                    $purchase->sale_price,
                                            );
                                            $totalNetProfitLoss = $totalSalePrice - $totalActualCost;
                                        @endphp
                                        <tr>
                                            <th colspan="4" class="text-end p-1 m-0">Total</th>
                                            <th class="text-end p-1 m-0">{{ number_format($purchases->sum('price'), 2) }}
                                            </th>
                                            <th class="text-end p-1 m-0">{{ number_format($purchases->sum('expense'), 2) }}
                                            </th>
                                            <th class="text-end p-1 m-0">{{ number_format($totalExpenseAdjustments, 2) }}
                                            </th>
                                            <th class="text-end p-1 m-0">{{ number_format($totalProfitAdjustments, 2) }}
                                            </th>
                                            <th class="text-end p-1 m-0">{{ number_format($totalActualCost, 2) }}</th>
                                            <th class="text-end p-1 m-0">{{ number_format($totalSalePrice, 2) }}</th>
                                            <th
                                                class="text-end p-1 m-0 {{ $totalNetProfitLoss < 0 ? 'text-danger' : 'text-success' }}">
                                                {{ number_format($totalNetProfitLoss, 2) }}</th>
                                            <th></th>
                                        </tr>

                                    </tfoot>
                                </table>
                            </div>

                            <div class="card-header">
                                <h3>Parts</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="table-active">
                                            <th scope="col" style="width: 50px;">#</th>
                                            <th scope="col">Date</th>
                                            <th scope="col" class="text-start">Part</th>
                                            <th scope="col" class="text-end">Purchase Price</th>
                                            <th scope="col" class="text-end">Sale Price</th>
                                            <th scope="col" class="text-end">Profit per Unit</th>
                                            <th scope="col" class="text-end">Sold</th>
                                            <th scope="col" class="text-end">Total Profit</th>
                                        </tr>
                                    </thead>
                                    <tbody id="products-list">
                                        @php
                                            $part_profit = 0;
                                        @endphp
                                        @foreach ($parts_sales as $key => $parts_sale)
                                            @php
                                                $ppu = $parts_sale->price - $parts_sale->pprice;
                                                $part_profit += $ppu * $parts_sale->qty;
                                            @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $parts_sale->date ? date('d M Y', strtotime($parts_sale->date)) : '-' }}
                                                </td>
                                                <td class="text-start">{{ $parts_sale->description }}</td>
                                                <td class="text-end">{{ number_format($parts_sale->pprice, 2) }}</td>
                                                <td class="text-end">{{ number_format($parts_sale->price, 2) }}</td>
                                                <td class="text-end">{{ number_format($ppu, 2) }}</td>
                                                <td class="text-end">{{ number_format($parts_sale->qty, 2) }}</td>
                                                <td class="text-end">{{ number_format($ppu * $parts_sale->qty, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="7" class="text-end p-1 m-0">Total</th>
                                            <th class="text-end p-1 m-0">
                                                {{ number_format($part_profit, 2) }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="card-header">
                                <h3>Extra Profit</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="table-active">
                                            <th>#</th>
                                            <th>Ref #</th>
                                            <th>Category</th>
                                            <th>Account</th>
                                            <th>Date</th>
                                            <th>Notes</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="products-list">
                                        @foreach ($extra_profits as $key => $tran)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $tran->refID }}</td>
                                                <td>{{ $tran->category->name }}</td>
                                                <td>{{ $tran->account->title }}</td>
                                                <td>{{ date('d M Y', strtotime($tran->date)) }}</td>
                                                <td>{{ $tran->notes }}</td>
                                                <td>{{ number_format($tran->amount) }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-end p-1 m-0">Total</th>
                                            <th class="text-end p-1 m-0">
                                                {{ number_format($extra_profits->sum('amount'), 2) }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="card-header">
                                <h3>Expenses</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                    <thead>
                                        <tr class="table-active">
                                            <th>#</th>
                                            <th>Ref #</th>
                                            <th>Category</th>
                                            <th>Account</th>
                                            <th>Date</th>
                                            <th>Notes</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="products-list">
                                        @foreach ($expenses as $key => $tran)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $tran->refID }}</td>
                                                <td>{{ $tran->category->name }}</td>
                                                <td>{{ $tran->account->title }}</td>
                                                <td>{{ date('d M Y', strtotime($tran->date)) }}</td>
                                                <td>{{ $tran->notes }}</td>
                                                <td>{{ number_format($tran->amount) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-end p-1 m-0">Total</th>
                                            <th class="text-end p-1 m-0">
                                                {{ number_format($expenses->sum('amount'), 2) }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="row mt-4 row-cols-5 g-3">
                        <div class="col">
                            <div class="card p-4">
                                <div class="card-header">
                                    <h3>Vehicles Profit</h3>
                                    <h5>{{ number_format($totalNetProfitLoss, 2) }}</h5>

                                </div>

                            </div>
                        </div>

                        <div class="col">
                            <div class="card p-4">
                                <div class="card-header">
                                    <h3>Parts Profit</h3>
                                    <h5>{{ number_format($part_profit, 2) }}</h5>

                                </div>

                            </div>
                        </div>
                        <div class="col">
                            <div class="card p-4">
                                <div class="card-header">
                                    <h3>Extra Profit</h3>
                                    <h5>{{ number_format($extra_profits->sum('amount'), 2) }}</h5>

                                </div>

                            </div>
                        </div>
                        <div class="col">
                            <div class="card p-4">
                                <div class="card-header">
                                    <h3>Expenses</h3>
                                    <h5>{{ number_format($expenses->sum('amount'), 2) }}</h5>

                                </div>

                            </div>
                        </div>
                        <div class="col">
                            <div class="card p-4">
                                <div class="card-header">
                                    <h3>Net Profit</h3>
                                    <h5>{{ number_format($totalNetProfitLoss + $part_profit + $extra_profits->sum('amount') - $expenses->sum('amount'), 2) }}
                                    </h5>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                        <div class="col-12 mt-4">
                            <div class="card p-4">
                                <div class="card-header">
                                    <h3>Investor Distributions</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th>#</th>
                                                <th>Investor</th>
                                                <th>Percentage</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($profit_distribution->details as $key => $detail)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $detail->account->title }}</td>
                                                    <td>{{ $detail->percentage }}%</td>
                                                    <td>{{ number_format($detail->amount, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end p-1 m-0">Total</th>
                                                <th class="p-1 m-0">
                                                    {{ number_format($profit_distribution->details->sum('amount'), 2) }}
                                                </th>
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
