@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h3> Create Purchase </h3>
                                </div>
                                <div class="col-6 d-flex flex-row-reverse"><button onclick="window.close()"
                                        class="btn btn-danger">Close</button></div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('purchase.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-2">
                                <div class="form-group mt-2">
                                    <label for="date">Purchase Date</label>
                                    <input type="date" name="date" id="date"
                                        value="{{ isset($lastpurchase) && $lastpurchase->date ? date('Y-m-d', strtotime($lastpurchase->date)) : date('Y-m-d') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group mt-2">
                                    <label for="type">Type</label>
                                    <select name="type" id="type" required class="form-control">
                                        <option value="Bike" @selected(old('type') == 'Bike')>Bike</option>
                                        <option value="Car" @selected(old('type') == 'Car')>Car</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group mt-2">
                                    <label for="engine">Engine No.</label>
                                    <input type="text" name="engine" id="engine" value="{{ old('engine') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group mt-2">
                                    <label for="chessis">Chassis No.</label>
                                    <input type="text" name="chassis" id="chessis" required
                                        value="{{ old('chassis') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group mt-2">
                                    <label for="company">Company</label>
                                    <input type="text" name="company" id="company" value="{{ old('company') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group mt-2">
                                    <label for="model">Model</label>
                                    <input type="text" name="model" id="model" value="{{ old('model') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group mt-2">
                                    <label for="color">Color</label>
                                    <input type="text" name="color" id="color" value="{{ old('color') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group mt-2">
                                    <label for="meter_type">Meter Type</label>
                                    <input type="text" name="meter_type" id="meter_type" value="{{ old('meter_type') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group mt-2">
                                    <label for="price">Price</label>
                                    <input type="number" name="price" id="price" oninput="updateChanges()"
                                        value="{{ old('price') ?? 0 }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group mt-2">
                                    <label for="expenses">Expenses</label>
                                    <input type="number" name="expenses" id="expenses" oninput="updateChanges()"
                                        value="{{ old('expenses') ?? 0 }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group mt-2">
                                    <label for="total_cost">Total Cost</label>
                                    <input type="number" name="total_cost" id="total_cost" readonly
                                        value="{{ old('total_cost') ?? 0 }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group mt-2">
                                    <label for="sale_price">Sale Price</label>
                                    <input type="number" name="sale_price" id="sale_price"
                                        value="{{ old('sale_price') ?? 0 }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group mt-2">
                                    <label for="invoice_no">Invoice No.</label>
                                    <input type="text" name="invoice_no" id="invoice_no"
                                        value="{{ old('invoice_no') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 col-md-2">
                                <div class="form-group mt-2">
                                    <label for="vendor">Vendor</label>
                                    <select name="vendor" id="vendor" required class="form-control">
                                        <option value="">Select Vendor</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-group mt-2">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" cols="30" rows="5">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Create Purchase</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
    <style>
        .no-padding {
            padding: 5px 5px !important;
        }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        function updateChanges() {
            var price = parseFloat($('#price').val());
            var expenses = parseFloat($('#expenses').val());

            var amount = (price + expenses);

            $("#total_cost").val(amount.toFixed(2));

        }
    </script>
@endsection
