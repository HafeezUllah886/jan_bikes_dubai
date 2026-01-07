@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-xxl-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="card-header border-bottom-dashed p-4">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h1>{{ projectNameHeader() }}</h1>
                                </div>
                                <div class="flex-shrink-0 mt-sm-0 mt-3">
                                    <h3>Approve Import</h3>
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
                                <form action="{{ route('imports.approve.store', $import->id) }}" method="POST">
                                    @csrf
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
                                                    <th class='no-padding'>Dubai Expense</th>
                                                    <th class='no-padding'>Net Cost</th>
                                                    <th class='no-padding'>Sale Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $car_expenses = 0;
                                                    $bike_expenses = 0;
                                                    $part_expenses = 0;

                                                    $expensePerCarJapan = 0;
                                                    $expensePerBikeJapan = 0;
                                                    $expensePerPartJapan = 0;

                                                    $expensePerCarDubai = 0;
                                                    $expensePerBikeDubai = 0;
                                                    $expensePerPartDubai = 0;

                                                    $total_cars = 0;
                                                    $total_bikes = 0;
                                                    $total_parts = 0;

                                                    if ($import->cars->where('type', 'Car')->count() > 0) {
                                                        $car_expenses = $import->car_expenses;
                                                        $total_cars = $import->cars->where('type', 'Car')->count();
                                                        $expensePerCarJapan = $car_expenses / $total_cars;
                                                        $expensePerCarDubai = $car_expense_dubai / $total_cars;
                                                    }
                                                    if ($import->cars->where('type', 'Bike')->count() > 0) {
                                                        $bike_expenses = $import->bike_expenses;
                                                        $total_bikes = $import->cars->where('type', 'Bike')->count();
                                                        $expensePerBikeJapan = $bike_expenses / $total_bikes;
                                                        $expensePerBikeDubai = $bike_expense_dubai / $total_bikes;
                                                    }
                                                    if ($import->parts->count() > 0) {
                                                        $part_expenses = $import->part_expenses;
                                                        $total_parts = $import->parts->sum('qty');
                                                        $expensePerPartJapan = $part_expenses / $total_parts;
                                                        $expensePerPartDubai = $part_expense_dubai / $total_parts;
                                                    }

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
                                                            <td class="text-end no-padding">
                                                                <input type="number" name="car_price[]" readonly
                                                                    value="{{ round($car->price, 4) }}"
                                                                    class="form-control">
                                                            </td>
                                                            <td class="text-end no-padding">
                                                                <input type="number" readonly
                                                                    name="expense_per_car_japan[]"
                                                                    value="{{ round($expensePerCarJapan, 4) }}"
                                                                    class="form-control">
                                                            </td>
                                                            <td class="text-end no-padding">
                                                                <input type="number" readonly
                                                                    name="expense_per_car_dubai[]"
                                                                    value="{{ round($expensePerCarDubai, 4) }}"
                                                                    class="form-control">
                                                            </td>
                                                            <td class="text-end no-padding">
                                                                <input type="number" readonly name="car_net_cost[]"
                                                                    value="{{ round($car->price + $expensePerCarJapan + $expensePerCarDubai, 4) }}"
                                                                    class="form-control">
                                                            </td>
                                                            <td class="text-end no-padding">
                                                                <input type="number" name="car_sale_price[]" required
                                                                    value="0" class="form-control">
                                                            </td>
                                                            <input type="hidden" name="car_id[]"
                                                                value="{{ $car->id }}">
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
                                                        {{ number_format(round($expensePerCarJapan * $total_cars, 4)) }}
                                                    </th>
                                                    <th class="text-end no-padding">
                                                        {{ number_format(round($expensePerCarDubai * $total_cars, 4)) }}
                                                    </th>
                                                    <th class="text-end no-padding">
                                                        {{ number_format(round($import->cars->where('type', 'Car')->sum('price') + $expensePerCarJapan * $total_cars + $expensePerCarDubai * $total_cars, 4)) }}
                                                    </th>
                                                    <th></th>
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
                                                    <th class='no-padding'>Dubai Expense</th>
                                                    <th class='no-padding'>Net Cost</th>
                                                    <th class='no-padding'>Sale Price</th>
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
                                                            <td class="text-end no-padding">
                                                                <input type="number" readonly name="car_price[]"
                                                                    value="{{ round($car->price, 4) }}"
                                                                    class="form-control">
                                                            </td>
                                                            <td class="text-end no-padding">
                                                                <input type="number" readonly
                                                                    name="expense_per_car_japan[]"
                                                                    value="{{ round($expensePerBikeJapan, 4) }}"
                                                                    class="form-control">
                                                            </td>
                                                            <td class="text-end no-padding">
                                                                <input type="number" readonly
                                                                    name="expense_per_car_dubai[]"
                                                                    value="{{ round($expensePerBikeDubai, 4) }}"
                                                                    class="form-control">
                                                            </td>
                                                            <td class="text-end no-padding">
                                                                <input type="number" readonly name="car_net_cost[]"
                                                                    value="{{ round($car->price + $expensePerBikeJapan + $expensePerBikeDubai, 4) }}"
                                                                    class="form-control">
                                                            </td>
                                                            <td class="text-end no-padding">
                                                                <input type="number" name="car_sale_price[]" required
                                                                    value="0" class="form-control">
                                                            </td>
                                                            <input type="hidden" name="car_id[]"
                                                                value="{{ $car->id }}">
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
                                                        {{ number_format(round($expensePerBikeJapan * $total_bikes, 4)) }}
                                                    </th>
                                                    <th class="text-end no-padding">
                                                        {{ number_format(round($expensePerBikeDubai * $total_bikes, 4)) }}
                                                    </th>
                                                    <th class="text-end no-padding">
                                                        {{ number_format(round($import->cars->where('type', 'Bike')->sum('price') + $expensePerBikeJapan * $total_bikes + $expensePerBikeDubai * $total_bikes, 4)) }}
                                                    </th>
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

                                                    <th class='no-padding text-center'>Price</th>
                                                    <th class='no-padding text-center'>Japan Expense</th>
                                                    <th class='no-padding text-center'>Dubai Expense</th>
                                                    <th class='no-padding text-center'>Quantity</th>
                                                    <th class='no-padding text-center'>Net Cost / Piece</th>
                                                    <th class='no-padding text-center'>Sale Price / Piece</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($import->parts as $part)
                                                    @php
                                                        $part_price = $part->price / $part->qty;
                                                    @endphp
                                                    <tr>
                                                        <td class='no-padding'>{{ $part->part_name }}</td>

                                                        <td class='no-padding text-center'>
                                                            <input type="number" readonly name="part_price[]"
                                                                value="{{ round($part_price, 4) }}" class="form-control">
                                                        </td>
                                                        <td class="text-center no-padding">
                                                            <input type="number" readonly name="part_japan_expense[]"
                                                                value="{{ round($expensePerPartJapan, 4) }}"
                                                                class="form-control">
                                                        </td>
                                                        <td class="text-center no-padding">
                                                            <input type="number" readonly name="part_dubai_expense[]"
                                                                value="{{ round($expensePerPartDubai, 4) }}"
                                                                class="form-control">
                                                        </td>
                                                        <td class='no-padding text-center'>{{ $part->qty }}</td>
                                                        <td class="text-center no-padding">
                                                            <input type="number" readonly name="part_net_cost[]"
                                                                value="{{ round($part_price + $expensePerPartJapan + $expensePerPartDubai, 4) }}"
                                                                class="form-control">
                                                        </td>
                                                        <td class="text-center no-padding">
                                                            <input type="number" required name="part_sale_price[]"
                                                                value="0" class="form-control">
                                                        </td>
                                                        <input type="hidden" name="part_id[]"
                                                            value="{{ $part->id }}">
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-active">
                                                    <th class="text-end no-padding">Total</th>

                                                    <th class="text-end no-padding text-center">
                                                        {{ number_format(round($import->parts->sum('price'), 4)) }}</th>
                                                    <th class="text-end no-padding text-center">
                                                        {{ number_format(round($expensePerPartJapan * $import->parts->sum('qty'), 4)) }}
                                                    </th>
                                                    <th class="text-end no-padding text-center">
                                                        {{ number_format(round($expensePerPartDubai * $import->parts->sum('qty'), 4)) }}
                                                    </th>
                                                    <th class="text-end no-padding text-center">
                                                        {{ number_format(round($import->parts->sum('qty'), 4)) }}</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Approve</button>
                                </form>
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
