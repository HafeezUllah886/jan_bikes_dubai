@extends('layout.popups')
@section('content')
    <script>
        var existingParts = [];

        @foreach ($export->export_parts as $part)
            @php
                $partID = $part->purchase_id;
            @endphp
            existingParts.push({{ $partID }});
        @endforeach

        var existingProducts = [];

        @foreach ($export->export_cars as $car)
            @php
                $carID = $car->purchase_id;
            @endphp
            existingProducts.push({{ $carID }});
        @endforeach
    </script>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h3> Edit Export </h3>
                                </div>
                                <div class="col-6 d-flex flex-row-reverse"><button onclick="window.close()"
                                        class="btn btn-danger">Close</button></div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('export.update', $export->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="product">Bike / Car</label>
                                            <select name="product" class="selectize" id="product">
                                                <option value=""></option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->engine }} |
                                                        {{ $product->chassis }} | {{ $product->company }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <th width="">Chassis</th>
                                                <th class="text-start">Company</th>
                                                <th class="text-start">Model</th>
                                                <th width="" class="text-center">Yen</th>
                                                <th width="" class="text-center">Rate</th>
                                                <th width="" class="text-center">Dirham</th>
                                                <th></th>
                                            </thead>
                                            <tbody id="products_list">
                                                @foreach ($export->export_cars as $product)
                                                    @php
                                                        $id = $product->purchase_id;
                                                    @endphp
                                                    <tr id="row_{{ $id }}" class="no-padding">
                                                        <td class="no-padding text-start">{{ $product->purchase->chassis }}
                                                        </td>
                                                        <td class="no-padding text-start">{{ $product->purchase->company }}
                                                        </td>
                                                        <td class="no-padding text-start">{{ $product->purchase->model }}
                                                        </td>
                                                        <td class="no-padding"><input type="number" name="car_price[]"
                                                                readonly required step="any"
                                                                value="{{ $product->yen }}" min="0"
                                                                class="form-control form-control-sm text-center"
                                                                id="price_{{ $id }}"></td>
                                                        <td class="no-padding text-center">{{ $product->purchase->rate }}
                                                        </td>
                                                        <td class="no-padding"><input type="number" name="car_dirham[]"
                                                                readonly required step="any"
                                                                value="{{ $product->dirham }}" min="0"
                                                                class="form-control form-control-sm text-center"
                                                                id="dirham_{{ $id }}"></td>
                                                        <td class="no-padding"><span class="btn btn-sm btn-danger mt-0"
                                                                onclick="deleteRow({{ $id }})">X</span> </td>
                                                        <input type="hidden" name="car_id[]" value="{{ $id }}">
                                                        <input type="hidden" name="car_tax[]"
                                                            value="{{ $product->purchase->ptax }}">
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-end">Total</th>
                                                    <th class="text-center" id="totalPrice">0.00</th>
                                                    <th></th>
                                                    <th class="text-center" id="totalDirham">0.00</th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="part">Others (Parts / Oil / Bicycles)</label>
                                            <select name="part" class="selectize2" id="part">

                                                <option value="">Select Part</option>
                                                @foreach ($parts as $part)
                                                    <option value="{{ $part->id }}">{{ $part->part_name }} |
                                                        {{ $part->avail_qty }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <th width="60%">Description</th>
                                                <th width="" class="text-center">Qty</th>
                                                <th width="" class="text-center">Yen</th>
                                                <th width="" class="text-center">Dirham</th>
                                                <th></th>
                                            </thead>
                                            <tbody id="parts_list">
                                                @foreach ($export->export_parts as $part)
                                                    @php
                                                        $id = $part->purchase_id;
                                                        $qty = get_available_part_qty($id);
                                                    @endphp
                                                    <tr id="part_row_{{ $id }}" class="no-padding">
                                                        <td class="no-padding text-start">{{ $part->part_name }}</td>
                                                        <td class="no-padding"><input type="number" name="qty[]"
                                                                required step="any" value="{{ $part->qty }}"
                                                                min="0" max="{{ $qty + $part->qty }}"
                                                                oninput="calculatePartAmount({{ $id }})"
                                                                class="form-control form-control-sm text-center"
                                                                id="qty_{{ $id }}"></td>
                                                        <td class="no-padding"><input type="number" name="part_yen[]"
                                                                readonly required step="any"
                                                                value="{{ $part->yen }}" min="0"
                                                                class="form-control form-control-sm text-center"
                                                                id="part_yen_{{ $id }}"></td>
                                                        <td class="no-padding"><input type="number" name="part_dirham[]"
                                                                readonly required step="any"
                                                                value="{{ $part->dirham }}" min="0"
                                                                class="form-control form-control-sm text-center"
                                                                id="part_dirham_{{ $id }}"></td>
                                                        <td class="no-padding"> <span class="btn btn-sm btn-danger mt-0"
                                                                onclick="deletePartRow({{ $id }})">X</span>
                                                        </td>
                                                        <input type="hidden" name="part_id[]"
                                                            value="{{ $id }}">
                                                        <input type="hidden" name="part_tax[]"
                                                            value="{{ $part->purchase->tax }}">
                                                        <input type="hidden" name="part_price[]"
                                                            id="part_price_{{ $id }}" step="any"
                                                            value="{{ $part->purchase->price }}">
                                                        <input type="hidden" name="part_dirham_price[]"
                                                            id="part_dirham_price_{{ $id }}" step="any"
                                                            value="{{ $part->purchase->dirham_price }}">
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2" class="text-end">Total</th>
                                                    <th class="text-center" id="totalPartYen">0.00</th>
                                                    <th class="text-center" id="totalPartDirham">0.00</th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="inv_no">Inv #</label>
                                    <input type="text" class="form-control" required name="inv_no"
                                        value="{{ $export->inv_no }}">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="c_no">C/No</label>
                                    <input type="text" class="form-control" required name="c_no"
                                        value="{{ $export->c_no }}">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group ">
                                    <label for="consignee">Consignee</label>
                                    <select name="consignee" required class="selectize1" id="consignee">
                                        <option value=""></option>
                                        @foreach ($consignees as $consignee)
                                            <option value="{{ $consignee->id }}"
                                                {{ $consignee->id == $export->consignee_id ? 'selected' : '' }}>
                                                {{ $consignee->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group ">
                                    <label for="info_party">Info Party</label>
                                    <select name="info_party" required class="selectize1" id="info_party">
                                        <option value=""></option>
                                        @foreach ($consignees as $consignee)
                                            <option value="{{ $consignee->id }}"
                                                {{ $consignee->id == $export->info_party_id ? 'selected' : '' }}>
                                                {{ $consignee->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control" required name="date"
                                        value="{{ date('Y-m-d', strtotime($export->date)) }}">
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="expense">Total Purchase (Dirham)</label>
                                    <input type="number" class="form-control" id="total" readonly step="any"
                                        name="total" value="{{ $export->total }}">
                                </div>
                            </div>
                            {{--  <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="expense">Transport Expense (Yen)</label>
                                    <input type="number" class="form-control" id="expense" step="any"
                                        oninput="calculateDirham()" name="expense"
                                        value="{{ $export->transport_charges }}">
                                </div>
                            </div> --}}
                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="expense">Bikes Expense (Yen)</label>
                                    <input type="number" class="form-control" id="other_expense" step="any"
                                        oninput="calculateDirham()" name="otherexpense"
                                        value="{{ $export->other_expenses }}">
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="expense">Cars Expense (Yen)</label>
                                    <input type="number" class="form-control" id="car_expense" step="any"
                                        oninput="calculateDirham()" name="car_expense"
                                        value="{{ $export->cars_expense }}">
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="expense">Parts Expense (Yen)</label>
                                    <input type="number" class="form-control" id="parts_expense" step="any"
                                        oninput="calculateDirham()" name="parts_expense"
                                        value="{{ $export->parts_expense }}">
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="rate">Exchange Rate</label>
                                    <input type="number" class="form-control" step="any" name="rate"
                                        oninput="calculateDirham()" id="rate" value="{{ $export->rate }}">
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="rate">Total Expense (Dirham)</label>
                                    <input type="number" class="form-control" step="any" name="total_expense"
                                        readonly id="total_expense" value="{{ $export->total_exp }}">

                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="rate">Total (Dirham)</label>
                                    <input type="number" class="form-control" step="any" name="net" readonly
                                        id="net" value="{{ $export->total }}">

                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Update Export</button>
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
        $(".selectize").selectize({
            onChange: function(value) {
                if (!value.length) return;
                if (value != 0) {
                    getSingleProduct(value);
                    this.clear();
                    this.focus();
                }

            },
        });
        $(".selectize2").selectize({
            onChange: function(value) {
                if (!value.length) return;
                if (value != 0) {
                    getSinglePart(value);
                    this.clear();
                    this.focus();
                }

            },
        });


        function getSingleProduct(id) {
            $.ajax({
                url: "{{ url('export/getproduct/') }}/" + id,
                method: "GET",
                success: function(product) {
                    let found = $.grep(existingProducts, function(element) {
                        return element === product.id;
                    });
                    if (found.length > 0) {

                    } else {
                        var id = product.id;
                        var html = '<tr id="row_' + id + '" class="no-padding">';
                        html += '<td class="no-padding text-start">' + product.chassis + '</td>';
                        html += '<td class="no-padding text-start">' + product.company + '</td>';
                        html += '<td class="no-padding text-start">' + product.model + '</td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="car_price[]" oninput="updateTotal()" readonly required step="any" value="' +
                            product.total +
                            '" min="0" class="form-control form-control-sm text-center" id="price_' + id +
                            '"></td>';
                        html += '<td class="no-padding text-center">' + product.rate + '</td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="car_dirham[]" oninput="updateTotal()" readonly required step="any" value="' +
                            product.net_dirham +
                            '" min="0" class="form-control form-control-sm text-center" id="dirham_' + id +
                            '"></td>';
                        html +=
                            '<td class="no-padding"><span class="btn btn-sm btn-danger mt-0" onclick="deleteRow(' +
                            id + ')">X</span> </td>';
                        html += '<input type="hidden" name="car_id[]" value="' + id + '">';
                        html += '<input type="hidden" name="car_tax[]" value="' + product.ptax + '">';
                        html += '</tr>';
                        $("#products_list").prepend(html);
                        updateTotal();
                        existingProducts.push(id);
                    }
                }
            });
        }

        function updateTotal() {

            var totalPrice = 0;
            $("input[id^='price_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                totalPrice += parseFloat(inputValue);
            });

            $("#totalPrice").html(totalPrice.toFixed(2));

            var totalDirham = 0;
            $("input[id^='dirham_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                totalDirham += parseFloat(inputValue);
            });

            $("#totalDirham").html(totalDirham.toFixed(2));

            var totalPartYen = 0;
            $("input[id^='part_yen_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                totalPartYen += parseFloat(inputValue);
            });

            $("#totalPartYen").html(totalPartYen.toFixed(2));

            var totalPartDirham = 0;
            $("input[id^='part_dirham_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                totalPartDirham += parseFloat(inputValue);
            });

            $("#totalPartDirham").html(totalPartDirham.toFixed(2));

            var total = totalDirham + totalPartDirham;

            $("#total").val(total.toFixed(2));

            var expense = parseFloat($("#total_expense").val());

            var net = total + expense;
            $("#net").val(net.toFixed(2));

        }

        function deleteRow(id) {
            existingProducts = $.grep(existingProducts, function(value) {
                return value !== id;
            });
            $('#row_' + id).remove();

            updateTotal();
        }

        function getSinglePart(id) {
            $.ajax({
                url: "{{ url('export/getpart/') }}/" + id,
                method: "GET",
                success: function(part) {
                    let found = $.grep(existingParts, function(element) {
                        return element === part.id;
                    });
                    if (found.length > 0) {} else {
                        var id = part.id;
                        var html = '<tr id="row_' + id + '" class="no-padding">';
                        html += '<td class="no-padding text-start">' + part.name + '</td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="qty[]" required step="any" value="' +
                            part.qty + '" min="0" max="' + part.qty + '" oninput="calculatePartAmount(' + id +
                            ')" class="form-control form-control-sm text-center" id="qty_' + id + '"></td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="part_yen[]" readonly required step="any" value="' +
                            part.amount +
                            '" min="0" class="form-control form-control-sm text-center" id="part_yen_' + id +
                            '"></td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="part_dirham[]" readonly required step="any" value="' +
                            part.dirham +
                            '" min="0" class="form-control form-control-sm text-center" id="part_dirham_' + id +
                            '"></td>';
                        html +=
                            '<td class="no-padding"> <span class="btn btn-sm btn-danger mt-0" onclick="deletePartRow(' +
                            id + ')">X</span> </td>';
                        html += '<input type="hidden" name="part_id[]" value="' + id + '">';
                        html += '<input type="hidden" name="part_tax[]" value="' + part.tax + '">';
                        html += '<input type="hidden" name="part_price[]" id="part_price_' + id +
                            '" step="any" value="' + part.price + '">';
                        html += '<input type="hidden" name="part_dirham_price[]" id="part_dirham_price_' + id +
                            '" step="any" value="' + part.dirham_price + '">';
                        html += '</tr>';
                        $("#parts_list").prepend(html);
                        updateTotal();
                        existingParts.push(id);
                    }
                }
            });
        }

        function deletePartRow(id) {

            existingParts = $.grep(existingParts, function(value) {
                return value !== id;
            });
            $('#part_row_' + id).remove();
            updateTotal();
        }

        $(".selectize1").selectize();

        function calculateDirham() {
            var rate = parseFloat($('#rate').val());
            var other_expense = parseFloat($('#other_expense').val());
            var car_expense = parseFloat($('#car_expense').val());
            var parts_expense = parseFloat($('#parts_expense').val());
            var dirham = rate * (other_expense + car_expense + parts_expense);
            $('#total_expense').val(dirham.toFixed(2));
            updateTotal();
        }

        function calculatePartAmount(id) {
            var qty = parseFloat($('#qty_' + id).val());
            var price = parseFloat($('#part_price_' + id).val());
            var dirham_price = parseFloat($('#part_dirham_price_' + id).val());
            var amount = qty * price;
            var dirham_amount = qty * dirham_price;
            $('#part_yen_' + id).val(amount.toFixed(2));
            $('#part_dirham_' + id).val(dirham_amount.toFixed(2));
            updateTotal();
        }

        updateTotal();
    </script>
@endsection
