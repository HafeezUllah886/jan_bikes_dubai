@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-9">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                            <a href="javascript:window.print()" class="btn btn-primary ml-4"><i
                                    class="ri-printer-line mr-4"></i> Print</a>
                        </div>
                        <div class="card-header border-bottom-dashed p-4">
                            {{--   @include('layout.header') --}}
                        </div>
                        <!--end card-header-->
                    </div><!--end col-->
                    <div class="col-lg-12 ">
                        <div class="row">
                            <div class="col-4"></div>
                            <div class="col-4 text-center">
                                <h2>SALES INVOICE</h2>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-2">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Inv #</p>
                                    <h5 class="fs-14 mb-0">{{ $sale->id }}</h5>
                                </div>
                                <div class="col-4">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Customer</p>
                                    <h5 class="fs-14 mb-0">{{ $sale->customer->title }}</h5>
                                    <h6 class="fs-14 mb-0">
                                        {{ $sale->customerID != 2 ? $sale->customer->address : $sale->customerName }}</h6>
                                </div>
                                <div class="col-3">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                    <h5 class="fs-14 mb-0">{{ date('d M Y', strtotime($sale->date)) }}</h5>
                                </div>

                                <!--end col-->
                                <!--end col-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--end card-body-->
                    </div><!--end col-->
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            @if ($sale->sale_cars->count() > 0)
                                <h6>Vehicles</h6>
                                <div class="table-responsive">

                                    <table class="table table-bordered text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col" style="width: 50px;">#</th>
                                                <th scope="col" class="text-start">Chassis</th>
                                                <th scope="col" class="text-start">Model</th>
                                                <th scope="col" class="text-start">Color</th>
                                                <th scope="col" class="text-start">Company</th>
                                                <th scope="col" class="text-end">Price</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products-list">
                                            @foreach ($sale->sale_cars as $sale_car)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="text-start">{{ $sale_car->chassis }}</td>
                                                    <td class="text-start">{{ $sale_car->purchase->model }}</td>
                                                    <td class="text-start">{{ $sale_car->purchase->color }}</td>
                                                    <td class="text-start">{{ $sale_car->purchase->company }}</td>
                                                    <td class="text-end">{{ number_format($sale_car->price) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-end">Total:</td>
                                                <td class="text-end">{{ number_format($sale->sale_cars->sum('price')) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table><!--end table-->
                                </div>
                            @endif
                            @if ($sale->sale_parts->count() > 0)
                                <h6 class="mt-3">Parts</h6>
                                <div class="table-responsive">

                                    <table class="table table-bordered text-center table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr class="table-active">
                                                <th scope="col" style="width: 50px;">#</th>
                                                <th scope="col" class="text-start">Description</th>
                                                <th scope="col" class="text-start">Quantity</th>
                                                <th scope="col" class="text-start">Unit Price</th>
                                                <th scope="col" class="text-start">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products-list">
                                            @foreach ($sale->sale_parts as $sale_part)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="text-start">{{ $sale_part->description }}</td>
                                                    <td class="text-end">{{ $sale_part->qty }}</td>
                                                    <td class="text-end">{{ $sale_part->price }}</td>
                                                    <td class="text-end">{{ $sale_part->amount }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-end">Total:</td>
                                                <td class="text-end">{{ number_format($sale->sale_parts->sum('amount')) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table><!--end table-->
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            @if ($sale->notes != '')
                                <p><strong>Notes: </strong>{{ $sale->notes }}</p>
                            @endif
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
    <link rel="stylesheet" href="{{ asset('assets/libs/datatable/datatable.bootstrap5.min.css') }}" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="{{ asset('assets/libs/datatable/responsive.bootstrap.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/libs/datatable/buttons.dataTables.min.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Noto Nastaliq Urdu' rel='stylesheet'>
    <style>
        .urdu {
            font-family: 'Noto Nastaliq Urdu';
            font-size: 12px;
        }
    </style>
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
