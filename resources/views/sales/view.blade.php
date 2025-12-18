@extends('layout.popups')
@section('content')
        <div class="row justify-content-center">
            <div class="col-xxl-9">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                                <a href="javascript:window.print()" class="btn btn-success ml-4"><i class="ri-printer-line mr-4"></i> Print</a>
                            </div>
                            <div class="card-header border-bottom-dashed p-4">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                     <h1>JAN TRADING COMPANY</h1>
                                    </div>
                                    <div class="flex-shrink-0 mt-sm-0 mt-3">
                                        <h3>Sales Report</h3>
                                    </div>
                                </div>
                            </div>
                            <!--end card-header-->
                        </div><!--end col-->
                        <div class="col-lg-12">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Sale Date</p>
                                        <h5 class="fs-14 mb-0">{{ date('d M Y', strtotime($sale->date)) }}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Sale #</p>
                                        <h5 class="fs-14 mb-0">{{ $sale->id }}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Container #</p>
                                        <h5 class="fs-14 mb-0">{{ $sale->purchase->c_no }}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">BL #</p>
                                        <h5 class="fs-14 mb-0">{{ $sale->purchase->bl_no }}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Purchase Amount</p>
                                        @php
                                            $purchaseAmount = $sale->cars->sum('pprice') + $sale->parts->sum('pprice');
                                            $bl_amount = $sale->purchase->bl_amount_pkr;
                                            $expense = $sale->cars->sum('expense') + $sale->parts->sum('expense');
                                        @endphp
                                        <h5 class="fs-14 mb-0">{{ number_format($purchaseAmount, 2) }}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Expense</p>
                                        <h5 class="fs-14 mb-0">{{ number_format($expense, 2) }}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Total Cost</p>
                                        <h5 class="fs-14 mb-0">{{ number_format($purchaseAmount + $expense + $bl_amount, 2) }}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Sale Amount</p>
                                        <h5 class="fs-14 mb-0">{{ number_format($sale->amount, 2) }}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Profit / Loose</p>
                                        <h5 class="fs-14 mb-0">{{ number_format($sale->amount - $purchaseAmount - $expense, 2) }}</h5>
                                    </div>
                                   
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
                        <div class="col-lg-12">

                            <div class="card-body p-4 pt-0">
                                <h3>Cars</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center table-nowrap align-middle mb-0" >
                                        <thead>
                                            <tr class="table-active no-padding" >
                                                <th scope="col" class="no-padding" style="width: 50px;">#</th>
                                                <th class="text-start no-padding">Customer</th>
                                                <th class="no-padding text-start">Chassis</th>
                                                <th class="no-padding text-start">Description</th>
                                                <th class="no-padding">P-Price</th>
                                                <th class="no-padding">Expense</th>
                                                <th class="no-padding">Net Cost</th>
                                                <th class="no-padding">Sale Price</th>
                                                <th class="no-padding">Profit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                        @foreach ($sale->cars as $key => $item)
                                            <tr class="no-padding">
                                                <td class="no-padding">{{ $key + 1}}</td>
                                                <td class="text-start no-padding">{{ $item->customer->title }}</td>
                                                <td class="text-start no-padding">{{ $item->purchase->chassis_no }}</td>
                                                <td class="no-padding text-start">{{ $item->purchase->model }} | {{ $item->purchase->maker }} | {{ $item->purchase->year }} | {{ $item->purchase->color }} | {{ $item->purchase->grade }}</td>
                                                <td class="text-end no-padding">{{ number_format($item->pprice, 2) }}</td>
                                                <td class="text-end no-padding">{{ number_format($item->expense, 2) }}</td>
                                                <td class="text-end no-padding">{{ number_format($item->net_cost, 2) }}</td>
                                                <td class="text-end no-padding">{{ number_format($item->price, 2) }}</td>
                                                <td class="text-end no-padding">{{ number_format($item->profit, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end no-padding">Total</th>
                                                <th class="text-end no-padding">{{number_format($sale->cars->sum('pprice'), 2)}}</th>
                                                <th class="text-end no-padding">{{number_format($sale->cars->sum('expense'), 2)}}</th>
                                                <th class="text-end no-padding">{{number_format($sale->cars->sum('net_cost'), 2)}}</th>
                                                <th class="text-end no-padding">{{number_format($sale->cars->sum('price'), 2)}}</th>
                                                <th class="text-end no-padding">{{number_format($sale->cars->sum('profit'), 2)}}</th>
                                            </tr>
                                        </tfoot>
                                    </table><!--end table-->
                                </div>
                            </div>
                            <div class="card-body p-4 pt-0">
                                <h3>Parts</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center table-nowrap align-middle mb-0" >
                                        <thead>
                                            <tr class="table-active no-padding">
                                                <th scope="col" class="no-padding" style="width: 50px;">#</th>
                                                <th width="200px" class="text-start no-padding">Customer</th>
                                            <th width="" class="text-start no-padding">Description</th>
                                            <th width="" class="text-start no-padding">Grade</th>
                                            <th width="" class="text-start no-padding">Qty</th>
                                            <th width="" class="text-center no-padding">P-Price</th>
                                            <th width="" class="text-center no-padding">Expense</th>
                                            <th width="" class="text-center no-padding">Net Cost</th>
                                            <th width="" class="text-center no-padding">Sale Price</th>
                                            <th width="" class="text-center no-padding">Profit</th>
                                            </tr>
                                        </thead>
                                        <tbody >
                                        @foreach ($sale->parts as $key => $item)
                                            <tr class="no-padding">
                                                <td class="no-padding">{{ $key + 1}}</td>
                                                <td class="text-start no-padding">{{ $item->customer->title }}</td>
                                                <td class="text-start no-padding">{{ $item->purchase->description }} | {{ $item->purchase->weight_ltr }}</td>
                                                <td class="no-padding text-start">{{ $item->purchase->grade }}</td>
                                                <td class="no-padding">{{ $item->purchase->qty }}</td>
                                                <td class="text-end no-padding">{{ number_format($item->pprice, 2) }}</td>
                                                <td class="text-end no-padding">{{ number_format($item->expense, 2) }}</td>
                                                <td class="text-end no-padding">{{ number_format($item->net_cost, 2) }}</td>
                                                <td class="text-end no-padding">{{ number_format($item->price, 2) }}</td>
                                                <td class="text-end no-padding">{{ number_format($item->profit, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5" class="text-end no-padding">Total</th>
                                                <th class="text-end no-padding">{{number_format($sale->parts->sum('pprice'), 2)}}</th>
                                                <th class="text-end no-padding">{{number_format($sale->parts->sum('expense'), 2)}}</th>
                                                <th class="text-end no-padding">{{number_format($sale->parts->sum('net_cost'), 2)}}</th>
                                                <th class="text-end no-padding">{{number_format($sale->parts->sum('price'), 2)}}</th>
                                                <th class="text-end no-padding">{{number_format($sale->parts->sum('profit'), 2)}}</th>
                                            </tr>
                                        </tfoot>
                                    </table><!--end table-->
                                </div>
                            </div>
                            <!--end card-body-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->
@endsection
@section('page-css')

@endsection
@section('page-js')

@endsection


