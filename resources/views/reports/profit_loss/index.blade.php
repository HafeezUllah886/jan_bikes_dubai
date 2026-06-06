@extends('layout.app')
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>View Profit Loss Report</h3>
                </div>
                <form action="{{ route('reportProfitLossData') }}" method="get">
                    <div class="card-body">
                        <div class="form-group mt-2">
                            <label for="from">From</label>
                            <input type="date" name="from" id="from" value="{{ firstDayOfMonth() }}"
                                class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="to">To</label>
                            <input type="date" name="to" id="to" value="{{ lastDayOfMonth() }}"
                                class="form-control">
                        </div>

                        <div class="form-group mt-2">
                            <label for="invoice_id">Invoice No.</label>
                            <select name="invoice_id" id="invoice_id" class="selectize mt-2">
                                <option value="all">All Invoices</option>
                                @foreach ($invoices as $invoice)
                                    <option value="{{ $invoice }}">{{ $invoice }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <button class="btn btn-success w-100" id="viewBtn">View Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(".selectize").selectize();
    </script>
@endsection
