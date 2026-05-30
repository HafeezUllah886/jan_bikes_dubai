@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('stock.cars') }}" class="mb-3">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <span class="fw-semibold">Status:</span>
                    </div>
                    @foreach (['Available' => 'Available', 'Sold' => 'Sold', 'all' => 'All'] as $val => $label)
                        <div class="col-auto">
                            <a href="{{ route('stock.cars', ['status' => $val]) }}"
                                class="btn btn-sm {{ $status === $val ? 'btn-primary' : 'btn-outline-secondary' }}">
                                {{ $label }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </form>

            {{-- Summary Cards --}}
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1 small">Total Items</p>
                            <h4 class="mb-0 fw-bold">{{ $items->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1 small">Total Cost</p>
                            <h4 class="mb-0 fw-bold text-danger">{{ number_format($totalCost, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1 small">Total Sale Price</p>
                            <h4 class="mb-0 fw-bold text-info">{{ number_format($totalSalePrice, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1 small">Profit / Loss</p>
                            <h4 class="mb-0 fw-bold {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($totalProfit, 2) }}
                                <small class="fs-12">{{ $totalProfit >= 0 ? '▲ Profit' : '▼ Loss' }}</small>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        Cars Stock
                        <span
                            class="badge {{ $status === 'Available' ? 'bg-success' : ($status === 'Sold' ? 'bg-secondary' : 'bg-primary') }} ms-1">
                            {{ $status === 'all' ? 'All' : $status }}
                        </span>
                    </h5>
                </div>
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <table class="table table-hover" id="buttons-datatables">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Inv No.</th>
                                <th>Chassis</th>
                                <th>Description</th>
                                <th>Total Cost</th>
                                <th>Sale Price</th>
                                <th>Profit / Loss</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $key => $item)
                                @php
                                    $cost = $item->costWithExpenseProfit();
                                    $salePrice = $item->saleCar ? $item->saleCar->total : $item->sale_price;
                                    $pl = $salePrice - $cost;
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->date ? date('d M Y', strtotime($item->date)) : '-' }}</td>
                                    <td>{{ $item->inv_no ?? '-' }}</td>

                                    <td>{{ $item->chassis }}</td>
                                    <td>{{ $item->model }} {{ $item->company }} <span
                                            class="text-muted">{{ $item->color }}</span></td>
                                    <td class="fw-semibold">{{ number_format($cost, 2) }}</td>
                                    <td class="text-info fw-semibold">{{ number_format($salePrice, 2) }}</td>
                                    <td>
                                        @if ($item->status === 'Available')
                                            <span class="text-muted">—</span>
                                        @elseif ($pl >= 0)
                                            <span class="text-success fw-bold">+{{ number_format($pl, 2) }}</span>
                                        @else
                                            <span class="text-danger fw-bold">{{ number_format($pl, 2) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $item->status === 'Available' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('purchase.show', $item->id) }}"
                                            class="btn btn-sm btn-soft-secondary" target="_blank">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="5" class="text-end">Totals</td>
                                <td>{{ number_format($totalCost, 2) }}</td>
                                <td>{{ number_format($totalSalePrice, 2) }}</td>
                                <td class="{{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($totalProfit, 2) }}
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
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
