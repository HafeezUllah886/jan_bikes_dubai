@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-9">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                            @if ($import->status != 'Approved')
                                <button type="button" data-bs-toggle="modal" data-bs-target="#new"
                                    class="btn btn-primary ml-4">Approve</button>
                            @endif
                            <a href="javascript:window.print()" class="btn btn-success ml-4"><i
                                    class="ri-printer-line mr-4"></i> Print</a>
                        </div>
                        <div class="card-header border-bottom-dashed p-4">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h1>{{ projectNameHeader() }}</h1>
                                </div>
                                <div class="flex-shrink-0 mt-sm-0 mt-3">
                                    <h3>Import</h3>
                                </div>
                            </div>
                        </div>
                        <!--end card-header-->
                    </div><!--end col-->
                    <div class="col-lg-12 ">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                    <h5 class="fs-14 mb-0">{{ date('d M Y', strtotime($import->date)) }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Invoice No.</p>
                                    <h5 class="fs-14 mb-0">{{ $import->inv_no }}</h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Container No.</p>
                                    <h5 class="fs-14 mb-0">{{ $import->c_no }}</h5>
                                </div>

                                <div class="col-12 mt-1">
                                    <div class="card-header">
                                        <h4>Cars</h4>
                                    </div>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="table-active">
                                                <th class='no-padding'>Chassis No.</th>
                                                <th class='no-padding'>Company</th>
                                                <th class='no-padding'>Model</th>
                                                <th class='no-padding'>Engine No.</th>
                                                <th class='no-padding'>Color</th>
                                                <th class='no-padding'>Meter</th>
                                                <th class='no-padding'>Notes</th>
                                                <th class='no-padding'>Price</th>
                                                <th class='no-padding'>Japan Expense</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $car_expenses = $import->car_expenses;
                                                $bike_expenses = $import->bike_expenses;
                                                $part_expenses = $import->part_expenses;

                                                $total_cars = $import->cars->where('type', 'Car')->count();
                                                $total_bikes = $import->cars->where('type', 'Bike')->count();
                                                $total_parts = $import->parts->sum('qty');

                                                $expensePerCar = $car_expenses / $total_cars;
                                                $expensePerBike = $bike_expenses / $total_bikes;
                                                $expensePerPart = $part_expenses / $total_parts;

                                            @endphp
                                            @foreach ($import->cars as $car)
                                                @if ($car->type == 'Car')
                                                    <tr>
                                                        <td class='no-padding'>{{ $car->chassis }}</td>
                                                        <td class='no-padding'>{{ $car->company }}</td>
                                                        <td class='no-padding'>{{ $car->model }}</td>
                                                        <td class='no-padding'>{{ $car->engine }}</td>
                                                        <td class='no-padding'>{{ $car->color }}</td>
                                                        <td class='no-padding'>{{ $car->meter_type }}</td>
                                                        <td class='no-padding'>{{ $car->notes }}</td>
                                                        <td class="text-end no-padding">{{ number_format($car->price) }}
                                                        </td>
                                                        <td class="text-end no-padding">
                                                            {{ number_format($expensePerCar) }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-active">
                                                <th colspan="7" class="text-end no-padding">Total</th>
                                                <th class="text-end no-padding">
                                                    {{ number_format($import->cars->where('type', 'Car')->sum('price')) }}
                                                </th>
                                                <th class="text-end no-padding">
                                                    {{ number_format($expensePerCar * $total_cars) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="col-12 mt-1">
                                    <div class="card-header">
                                        <h4>Bikes</h4>
                                    </div>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="table-active">
                                                <th class='no-padding'>Chassis No.</th>
                                                <th class='no-padding'>Company</th>
                                                <th class='no-padding'>Model</th>
                                                <th class='no-padding'>Engine No.</th>
                                                <th class='no-padding'>Color</th>
                                                <th class='no-padding'>Meter</th>
                                                <th class='no-padding'>Notes</th>
                                                <th class='no-padding'>Price</th>
                                                <th class='no-padding'>Japan Expense</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($import->cars as $car)
                                                @if ($car->type == 'Bike')
                                                    <tr>
                                                        <td class='no-padding'>{{ $car->chassis }}</td>
                                                        <td class='no-padding'>{{ $car->company }}</td>
                                                        <td class='no-padding'>{{ $car->model }}</td>
                                                        <td class='no-padding'>{{ $car->engine }}</td>
                                                        <td class='no-padding'>{{ $car->color }}</td>
                                                        <td class='no-padding'>{{ $car->meter_type }}</td>
                                                        <td class='no-padding'>{{ $car->notes }}</td>
                                                        <td class="text-end no-padding">{{ number_format($car->price) }}
                                                        </td>
                                                        <td class="text-end no-padding">
                                                            {{ number_format($expensePerBike) }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-active">
                                                <th colspan="7" class="text-end no-padding">Total</th>
                                                <th class="text-end no-padding">
                                                    {{ number_format($import->cars->where('type', 'Bike')->sum('price')) }}
                                                </th>
                                                <th class="text-end no-padding">
                                                    {{ number_format($expensePerBike * $total_bikes) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="col-12 mt-1">
                                    <div class="card-header">
                                        <h4>Parts</h4>
                                    </div>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="table-active">
                                                <th class='no-padding'>Description</th>
                                                <th class='no-padding text-center'>Quantity</th>
                                                <th class='no-padding text-center'>Price</th>
                                                <th class='no-padding text-center'>Japan Expense</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($import->parts as $part)
                                                <tr>
                                                    <td class='no-padding'>{{ $part->part_name }}</td>
                                                    <td class='no-padding text-center'>{{ $part->qty }}</td>
                                                    <td class='no-padding text-center'>{{ $part->price }}</td>
                                                    <td class="text-center no-padding">
                                                        {{ number_format($expensePerPart) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-active">
                                                <th class="text-end no-padding">Total</th>
                                                <th class="text-end no-padding text-center">
                                                    {{ number_format($import->parts->sum('qty')) }}</th>
                                                <th class="text-end no-padding text-center">
                                                    {{ number_format($import->parts->sum('price')) }}</th>
                                                <th class="text-end no-padding text-center">
                                                    {{ number_format($expensePerPart * $import->parts->sum('qty')) }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                            <!--end row-->
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
    <div id="new" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Approve Import</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <form action="{{ route('imports.approve', $import->id) }}" method="get">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mt-2">
                            <label for="car_expense">Car Expense (Dubai)</label>
                            <input type="number" step="any" name="car_expense" required id="car_expense"
                                class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="bike_expense">Bike Expense (Dubai)</label>
                            <input type="number" step="any" name="bike_expense" required id="bike_expense"
                                class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="parts_expense">Parts Expense (Dubai)</label>
                            <input type="number" step="any" name="parts_expense" required id="parts_expense"
                                class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Continue</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/datatable/datatable.bootstrap5.min.css') }}" />
    <!--datatable responsive css-->
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
