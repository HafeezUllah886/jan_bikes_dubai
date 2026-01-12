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
                                    <h3> Create Sale </h3>
                                </div>
                                <div class="col-6 d-flex flex-row-reverse"><button onclick="window.close()"
                                        class="btn btn-danger">Close</button></div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('sale.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <th width="">Ser</th>
                                                <th width="">Type</th>
                                                <th width="">Chassis</th>
                                                <th class="text-start">Company</th>
                                                <th class="text-start">Model</th>
                                                <th width="" class="text-center">Price</th>
                                                <th width="" class="text-start">Profit</th>
                                                <th></th>
                                            </thead>
                                            <tbody id="products_list">
                                                @foreach ($products as $key => $product)
                                                    <tr id="car_row_{{ $product->id }}" class="no-padding">
                                                        <td class="no-padding text-start"><input type="checkbox"
                                                                name="car_id[]" id="car_id_{{ $product->id }}"
                                                                onclick="updateTotal()" value="{{ $product->id }}"> |
                                                            {{ $key + 1 }}</td>
                                                        <td class="no-padding">{{ $product->type }}</td>
                                                        <td class="no-padding">{{ $product->chassis }}</td>
                                                        <td class="no-padding">{{ $product->company }}</td>
                                                        <td class="no-padding">{{ $product->model }}</td>
                                                        <td class="no-padding"><input type="number" name="car_price[]"
                                                                id="car_price_{{ $product->id }}"
                                                                class="form-control form-control-sm text-center"
                                                                value="{{ $product->sale_price }}"></td>
                                                        <td class="no-padding">
                                                            <select name="car_profit[]"
                                                                class="form-control form-control-sm text-center"
                                                                id="car_profit_{{ $product->id }}">
                                                                <option value="Both">Both</option>
                                                                <option value="Only Profit">Only Profit</option>
                                                                <option value="Only Loss">Only Loss</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="5" class="text-end no-padding">Total</th>
                                                    <th class="text-end no-padding" id="totalPrice">
                                                        {{ number_format($products->sum('total'), 2) }}</th>
                                                    <th class="no-padding"></th>
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
                                            <label for="part">Parts</label>
                                            <select name="part" class="selectize2" id="part">

                                                <option value="">Select Part</option>
                                                @foreach ($parts as $part)
                                                    <option value="{{ $part->id }}">{{ $part->description }} |
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
                                                <th width="30%">Description</th>
                                                <th width="" class="text-center">Qty</th>
                                                <th width="" class="text-center">Price</th>
                                                <th width="" class="text-center">Amount</th>
                                                <th width="" class="text-start">Profit</th>
                                                <th></th>
                                            </thead>
                                            <tbody id="parts_list"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-end no-padding">Total</th>
                                                    <th class="text-center no-padding" id="totalPartPrice">0.00</th>
                                                    <th class="text-start no-padding"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="rate">Total </label>
                                    <input type="number" class="form-control" step="any" name="net" readonly
                                        id="net" value="1">
                                </div>
                            </div>
                            <div class="col-3 mt-2">
                                <div class="form-group ">
                                    <label for="customer">Customer</label>
                                    <select name="customer" required class="selectize1" id="customer">
                                        <option value=""></option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2 mt-2">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control" required name="date"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Create Sale</button>
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

        function checkproductCheckBox() {
            var checked = $('input[name="car_id[]"]:checked').length;
            $("input[id^='car_id_']").each(function() {
                updateTotal();
            });
        }

        function updateTotal() {

            var totalPrice = 0;

            // loop inside the checked checkboxes
            $('input[name="car_id[]"]:checked').each(function() {
                var id = $(this).val();
                var price = parseFloat($("#car_price_" + id).val());
                totalPrice += price;
            });

            $("#totalPrice").html(totalPrice.toFixed(2));


            var total_part_price = 0;
            $("input[id^='part_amount_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                total_part_price += parseFloat(inputValue);
            });

            $("#totalPartPrice").html(total_part_price.toFixed(2));

            var total = totalPrice + total_part_price;

            $("#net").val(total.toFixed(2));

        }

        function deleteRow(id) {
            existingProducts = $.grep(existingProducts, function(value) {
                return value !== id;
            });
            $('#row_' + id).remove();

            updateTotal();
        }

        var existingParts = [];

        function getSinglePart(id) {
            $.ajax({
                url: "{{ url('sale/getpart/') }}/" + id,
                method: "GET",
                success: function(part) {
                    let found = $.grep(existingParts, function(element) {
                        return element === part.id;
                    });
                    if (found.length > 0) {} else {
                        var id = part.id;
                        var html = '<tr id="row_' + id + '" class="no-padding">';
                        html += '<td class="no-padding text-start">' + part.description + '</td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="qty[]" required step="any" value="' +
                            part.qty + '" min="0" max="' + part.qty +
                            '" class="form-control form-control-sm text-center" oninput="part_amount(' + id +
                            ')" id="qty_' + id + '"></td>';
                        html +=
                            '<td class="no-padding"><input type="number" oninput="part_amount(' + id +
                            ')" name="part_price[]" required step="any" value="' +
                            part.sale_price +
                            '" min="0" class="form-control form-control-sm text-center" id="part_price_' +
                            id + '"></td>';
                        html +=
                            '<td class="no-padding"><input type="number" name="part_amount[]" readonly required step="any" value="' +
                            part.amount +
                            '" min="0" class="form-control form-control-sm text-center" id="part_amount_' + id +
                            '"></td>';
                        html +=
                            ' <td class="no-padding"><select name="part_profit[]" class="form-control form-control-sm text-center" id="part_profit_' +
                            id + '">' +
                            '<option value="Both">Both</option>' +
                            '<option value="Only Profit">Only Profit</option>' +
                            '<option value="Only Loss">Only Loss</option>' +
                            '</select></td>';
                        html +=
                            '<td class="no-padding"> <span class="btn btn-sm btn-danger mt-0" onclick="deleteRow(' +
                            id + ')">X</span> </td>';
                        html += '<input type="hidden" name="part_id[]" value="' + id + '">';
                        html += '</tr>';
                        $("#parts_list").prepend(html);
                        part_amount(id);
                        updateTotal();
                        existingParts.push(id);
                    }
                }
            });
        }

        function part_amount(id) {
            var qty = parseFloat($("#qty_" + id).val());
            var price = parseFloat($("#part_price_" + id).val());
            var amount = qty * price;
            $("#part_amount_" + id).val(amount.toFixed(2));
            updateTotal();
        }

        function deletePart(id) {

            existingParts = $.grep(existingParts, function(value) {
                return value !== id;
            });
            $('#row_' + id).remove();
            updateTotal();
        }

        $(".selectize1").selectize();
    </script>
@endsection
