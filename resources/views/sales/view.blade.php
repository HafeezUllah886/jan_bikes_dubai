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
                            <div class="row">
                                <div class="col-9">
                                    <h1>JAN BIKES</h1>
                                </div>
                                <div class="col-3">
                                    <h1>NO: {{ $export->inv_no }}</h1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="row border-bottom border-dark pb-0 border-3">
                                        <div class="col-6">
                                            <h1 class="mb-0">INVOICE</h1>
                                        </div>
                                        {{--  <div class="col-6">
                                            <h1 class="mb-0">&yen;{{ number_format($export->total) }}</h1>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <h3>C/NO: {{ $export->c_no }}</h3>
                                </div>
                                <div class="col-6 text-center">
                                    <h5>DATE: {{ date('d M Y', strtotime($export->date)) }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12 ">
                                <div class="row">
                                    <div class="col-8">
                                        <h4 class="ps-4 border-bottom border-top border-dark border-2">BIKES</h4>
                                        <table class="table table-bordered"
                                            style="border-bottom: 2px solid black !important;">
                                            <tbody>
                                                @php
                                                    $total_bike_yen = 0;
                                                    $total_bike_dirham = 0;
                                                @endphp
                                                @foreach ($export->export_cars as $key => $car)
                                                    @if ($car->type == 'Bike')
                                                        @php
                                                            $total_bike_yen += $car->yen;
                                                            $total_bike_dirham += $car->purchase->net_dirham;
                                                        @endphp
                                                        <tr class="p-0 m-0">
                                                            <td class="p-1 pt-0 pb-0 top-border text-uppercase">
                                                                {{ $key + 1 }} </td>
                                                            <td class="p-1 pt-0 pb-0 top-border text-uppercase">
                                                                {{ $car->purchase->company }}</td>
                                                            <td class="p-1 pt-0 pb-0 top-border text-uppercase">
                                                                {{ $car->chassis }}</td>
                                                            <td class="p-1 pt-0 pb-0 top-border text-uppercase">
                                                                {{ $car->purchase->engine }}</td>
                                                            <td class="p-1 pt-0 pb-0 top-border text-uppercase text-end">
                                                                &yen;{{ number_format($car->yen) }}</td>
                                                            <td class="p-1 pt-0 pb-0 top-border text-uppercase text-end">
                                                                {{ round($car->purchase->rate, 5) }}</td>
                                                            <td class="p-1 pt-0 pb-0 top-border text-uppercase text-end">
                                                                {{ round($car->purchase->net_dirham, 2) }}</td>
                                                            <td class="p-1 pt-0 pb-0 m-0 border-0 text-uppercase">
                                                                ({{ $car->type }})
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr>
                                                    <td colspan="4" class="text-end p-1 pt-0 pb-0 ">Total:</td>
                                                    <td class="text-end p-1 pt-0 pb-0 ">
                                                        &yen;{{ number_format($total_bike_yen) }}</td>
                                                    <td></td>
                                                    <td class="text-end p-1 pt-0 pb-0 ">{{ round($total_bike_dirham, 2) }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <h4 class="ps-4 border-bottom border-top border-dark border-2">CARS</h4>
                                        <table class="table table-bordered"
                                            style="border-bottom: 2px solid black !important;">
                                            <tbody>
                                                @php
                                                    $total_car_yen = 0;
                                                    $total_car_dirham = 0;
                                                @endphp
                                                @foreach ($export->export_cars as $key => $car)
                                                    @if ($car->type == 'Car')
                                                        @php
                                                            $total_car_yen += $car->yen;
                                                            $total_car_dirham += $car->purchase->net_dirham;
                                                        @endphp
                                                        <tr class="p-0 m-0">
                                                            <td class="p-1 pt-0 pb-0 top-border text-uppercase">
                                                                {{ $key + 1 }} </td>
                                                            <td class="p-1 pt-0 pb-0 top-border text-uppercase">
                                                                {{ $car->purchase->company }}</td>
                                                            <td class="p-1 pt-0 pb-0 top-border text-uppercase">
                                                                {{ $car->chassis }}</td>
                                                            <td class="p-1 pt-0 pb-0 top-border text-uppercase">
                                                                {{ $car->purchase->engine }}</td>
                                                            <td class="p-1 pt-0 pb-0 top-border text-uppercase text-end">
                                                                &yen;{{ number_format($car->yen) }}</td>
                                                            <td class="p-1 pt-0 pb-0 top-border text-uppercase text-end">
                                                                {{ round($car->purchase->rate, 5) }}</td>
                                                            <td class="p-1 pt-0 pb-0 top-border text-uppercase text-end">
                                                                {{ round($car->purchase->net_dirham, 2) }}</td>
                                                            <td class="p-1 pt-0 pb-0 m-0 border-0 text-uppercase">
                                                                ({{ $car->type }})
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr>
                                                    <td colspan="4" class="text-end p-1 pt-0 pb-0 ">Total:</td>
                                                    <td class="text-end p-1 pt-0 pb-0 ">
                                                        &yen;{{ number_format($total_car_yen) }}</td>
                                                    <td></td>
                                                    <td class="text-end p-1 pt-0 pb-0 ">{{ round($total_car_dirham, 2) }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-4">
                                        <h4 class="ps-4 border-bottom border-top border-dark border-2">PARTS</h4>
                                        <table class="table table-borderless"
                                            style="border-bottom: 2px solid black !important;">
                                            <tbody>
                                                @foreach ($export->export_parts as $part)
                                                    <tr class="p-0 m-0">
                                                        <td class="p-0 top-border text-uppercase">{{ $part->part_name }}
                                                        </td>
                                                        <td class="p-0 top-border text-uppercase"> {{ $part->qty }}</td>
                                                        <td class="p-0 top-border text-uppercase text-end">
                                                            &yen;{{ $part->yen }}</td>
                                                        <td class="p-0 top-border text-uppercase text-end">
                                                            {{ round($part->dirham, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="2" class="text-end p-1 pt-0 pb-0 ">Total:</td>
                                                    <td class="text-end p-1 pt-0 pb-0 ">
                                                        &yen;{{ number_format($export->export_parts->sum('yen')) }}</td>
                                                    <td class="text-end p-1 pt-0 pb-0 ">
                                                        {{ round($export->export_parts->sum('dirham'), 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><!--end col-->
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <h5>Bikes Total:</h5>
                                                    <h5 class="text-strong">&yen;{{ number_format($total_bike_yen) }} |
                                                        Dirham
                                                        {{ round($total_bike_dirham, 2) }}</h5>
                                                </td>
                                                <td>
                                                    <h5>Cars Total:</h5>
                                                    <h5 class="text-strong">&yen;{{ number_format($total_car_yen) }} |
                                                        Dirham
                                                        {{ round($total_car_dirham, 2) }}</h5>
                                                </td>
                                                <td>
                                                    <h5>Parts Total:</h5>
                                                    <h5 class="text-strong">
                                                        &yen;{{ number_format($export->export_parts->sum('yen')) }} |
                                                        Dirham
                                                        {{ round($export->export_parts->sum('dirham'), 2) }}</h5>
                                                </td>
                                                <td>
                                                    <h5>Total Purchase:</h5>
                                                    <h5 class="text-strong">
                                                        &yen;{{ number_format($export->export_parts->sum('yen') + $total_bike_yen + $total_car_yen) }}
                                                        |
                                                        Dirham
                                                        {{ round($export->export_parts->sum('dirham') + $total_bike_dirham + $total_car_dirham, 2) }}
                                                    </h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5>Bikes Expense (&yen;):</h5>
                                                    <h5 class="text-strong">
                                                        {{ number_format($export->other_expenses) }}
                                                    </h5>
                                                </td>
                                                <td>
                                                    <h5>Car Expense (&yen;):</h5>
                                                    <h5 class="text-strong">{{ number_format($export->cars_expense) }}
                                                    </h5>
                                                </td>
                                                <td>
                                                    <h5>Parts Expense (&yen;):</h5>
                                                    <h5 class="text-strong">{{ number_format($export->parts_expense) }}
                                                    </h5>
                                                </td>
                                                <td>
                                                    <h5>Export Total (Dirham):</h5>
                                                    <h5 class="text-strong">
                                                        {{ number_format($export->total_exp, 2) }}
                                                    </h5>
                                                </td>
                                                <td>
                                                    <h5>Total Dirham</h5>
                                                    <h5 class="text-strong">
                                                        {{ number_format($export->total, 2) }}
                                                    </h5>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <table class="table-borderless">
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    <h5>CONSIGNEE:</h5>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <h5 class="text-uppercase">{{ $export->consignee->title }}</h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-end">
                                                    <h5>ADDRESS:</h5>
                                                </td>
                                                <td>
                                                    <h5 class="text-uppercase">{{ $export->consignee->address_one }}</h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <h5 class="text-uppercase">{{ $export->consignee->address_two }}</h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-end">
                                                    <h5>LICENSE NO:</h5>
                                                </td>
                                                <td>
                                                    <h5 class="text-uppercase">{{ $export->consignee->license_no }}</h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-end">
                                                    <h5>P.O BOX:</h5>
                                                </td>
                                                <td>
                                                    <h5 class="text-uppercase">{{ $export->consignee->po_box }}</h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-end">
                                                    <h5>TEL:</h5>
                                                </td>
                                                <td>
                                                    <h5 class="text-uppercase">{{ $export->consignee->tel }}</h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-end">
                                                    <h5>EMAIL:</h5>
                                                </td>
                                                <td>
                                                    <h5 class="text-uppercase">{{ $export->consignee->email }}</h5>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-6">
                                    <table class="table-borderless">
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    <h5>INFO PARTY:</h5>
                                                </td>
                                            </tr>
                                            @if ($export->info_party != $export->consignee)
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <h5 class="text-uppercase">{{ $export->info_party->title }}</h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">
                                                        <h5>ADDRESS:</h5>
                                                    </td>
                                                    <td>
                                                        <h5 class="text-uppercase">{{ $export->info_party->address_one }}
                                                        </h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <h5 class="text-uppercase">{{ $export->info_party->address_two }}
                                                        </h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">
                                                        <h5>LICENSE NO:</h5>
                                                    </td>
                                                    <td>
                                                        <h5 class="text-uppercase">{{ $export->info_party->license_no }}
                                                        </h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">
                                                        <h5>P.O BOX:</h5>
                                                    </td>
                                                    <td>
                                                        <h5 class="text-uppercase">{{ $export->info_party->po_box }}</h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">
                                                        <h5>TEL:</h5>
                                                    </td>
                                                    <td>
                                                        <h5 class="text-uppercase">{{ $export->info_party->tel }}</h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end">
                                                        <h5>EMAIL:</h5>
                                                    </td>
                                                    <td>
                                                        <h5 class="text-uppercase">{{ $export->info_party->email }}</h5>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="2">
                                                        <h5 class="text-uppercase">Same as Consignee</h5>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
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

        .top-border {
            border-top: 1px dotted black !important;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Arial';
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
