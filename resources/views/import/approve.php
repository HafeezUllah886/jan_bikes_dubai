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
                                <div class="col-6 d-flex flex-row-reverse"><a href="{{ route('purchase.index') }}"
                                        class="btn btn-danger">Close</a></div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('purchase.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <h4>Cars</h4>
                            </div>
                            <div class="col-6 d-flex flex-row-reverse"><button type="button" onclick="addCar()"
                                    class="btn btn-primary">Add Car</button></div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <th width="">Model</th>
                                            <th width="">Maker</th>
                                            <th width="">Chassis</th>
                                            <th width="">Auction</th>
                                            <th width="">Year</th>
                                            <th width="">Color</th>
                                            <th width="">Grade</th>
                                            <th width="">Price</th>
                                            <th width="">Price PKR</th>
                                            <th >Remarks</th>
                                            <th></th>
                                        </thead>
                                        <tbody id="cars_list">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="7" class="text-end">Total</th>
                                                <th class="text-center" id="totalPrice">0.00</th>
                                                <th class="text-center" id="totalPricePkr">0.00</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <h4>Oil / Parts</h4>
                            </div>
                            <div class="col-6 d-flex flex-row-reverse"><button type="button" onclick="addParts()"
                                    class="btn btn-primary">Add</button></div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <th width="">Description</th>
                                            <th width="">Weight / Ltr</th>
                                            <th width="">Grade</th>
                                            <th width="">Qty</th>
                                            <th width="">Price</th>
                                            <th width="">Price PKR</th>
                                            <th></th>
                                        </thead>
                                        <tbody id="parts_list">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end">Total</th>
                                                <th class="text-end" id="totalOilPartsQty">0.00</th>
                                                <th class="text-end" id="totalOilPartsPrice">0.00</th>
                                                <th class="text-end" id="totalOilPartsPricePkr">0.00</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                       

                       <div class="row">
                        <div class="col-2 mt-2">
                            <div class="form-group">
                                <label for="ex">Exchange Rate</label>
                                <input type="number" step="any" class="form-control text-center" oninput='updateRate()' id="ex" name="ex_rate"
                                    value="1">
                            </div>
                        </div>
                        <div class="col-2 mt-2">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" required name="date"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-2 mt-2">
                            <div class="form-group">
                                <label for="cno">C/No</label>
                                <input type="text" class="form-control" required name="cno">
                            </div>
                        </div>
                        <div class="col-2 mt-2">
                            <div class="form-group">
                                <label for="blno">BL/No</label>
                                <input type="text" class="form-control" required name="bl_no">
                            </div>
                        </div>
                        <div class="col-2 mt-2">
                            <div class="form-group">
                                <label for="blno">BL Amount</label>
                                <div class='input-group'>
                                <input type="number" class="form-control" oninput='updateRate()' step="any" value='0' id="bl_amount" required name="bl_amount">
                                <input type="number" readonly class="form-control" id="bl_amount_pkr" step="any" required name="bl_amount_pkr">
                                </div>

                            </div>
                        </div>
                        <div class="col-2 mt-2">
                            <div class="form-group">
                                <label for="g_total">Grand Total</label>
                                <div class='input-group'>
                                <input type="number" readonly class="form-control" id="g_total" step="any" required name="g_total">
                                <input type="number" readonly class="form-control" id="g_total_pkr" step="any" required name="g_total_pkr">
                            </div>
                            </div>
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
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize any existing select elements on page load
            $('select.selectize').selectize();
        });
        var car_id = 0;
        function addCar() {
            car_id++;
         
            var $newRow = $('<tr id="row_' + car_id + '">' +
                '<td><input type="text" name="model[]" required class="form-control"></td>' +
                '<td><input type="text" name="maker[]" required class="form-control"></td>' +
                '<td><input type="text" name="chassis[]" required class="form-control"></td>' +
                '<td><input type="text" name="auction[]" class="form-control"></td>' +
                '<td><input type="text" name="year[]" class="form-control"></td>' +
                '<td><input type="text" name="color[]" class="form-control"></td>' +
                '<td><input type="text" name="grade[]" class="form-control"></td>' +
                '<td><input type="number" name="price[]" required step="any" id="price_' + car_id + '" oninput="coverToPkr(' + car_id + ')")" class="form-control text-center"></td>' +
                '<td><input type="number" name="price_pkr[]" value="0" readonly step="any" id="price_pkr_' + car_id + '" class="form-control text-center"></td>' +
                '<td><input type="text" name="remarks[]" class="form-control"></td>' +
                '<td><span class="btn btn-sm btn-danger" onclick="deleteCar(' + car_id + ')">X</span></td>' +
                '<input type="hidden" name="car_id[]" id="car_id_' + car_id + '" value="' + car_id + '">' +
                '</tr>');

            // Append the new row
            $("#cars_list").append($newRow);
        }

        function deleteCar(id) {
            $('#row_' + id).remove();
            calculateTotal();
        }

        function coverToPkr(id) {
            var price = parseFloat($('#price_' + id).val());
            var ex = parseFloat($('#ex').val());
          
            var price_pkr = price * ex;
            $('#price_pkr_' + id).val(price_pkr.toFixed(2));
            calculateTotal();
        }

        function updateRate()
        {
            $("input[id^='car_id_']").each(function() {
               var id = $(this).val();
               coverToPkr(id);
            });

            $("input[id^='part_id_']").each(function() {
               var id = $(this).val();
               covertToPKRPart(id);
            });
        }

        function calculateTotal() {

            var totalCarPrice = 0;
            $('#cars_list tr').each(function() {
                var price = parseFloat($(this).find('input[name="price[]"]').val());
                if (!isNaN(price)) {
                    totalCarPrice += price;
                }
            });
            $('#totalPrice').text(totalCarPrice.toFixed(2));
            
            var totalCarPricePkr = 0;
            $('#cars_list tr').each(function() {
                var price = parseFloat($(this).find('input[name="price_pkr[]"]').val());
                if (!isNaN(price)) {
                    totalCarPricePkr += price;
                }
            });
            $('#totalPricePkr').text(totalCarPricePkr.toFixed(2));
            
            var totalPartsQty = 0;
            $('#parts_list tr').each(function() {
                var price = parseFloat($(this).find('input[name="part_qty[]"]').val());
                if (!isNaN(price)) {
                    totalPartsQty += price;
                }
            });
            $('#totalOilPartsQty').text(totalPartsQty.toFixed(2));
            
            var totalPartsPrice = 0;
            $('#parts_list tr').each(function() {
                var price = parseFloat($(this).find('input[name="part_price[]"]').val());
                if (!isNaN(price)) {
                    totalPartsPrice += price;
                }
            });
            $('#totalOilPartsPrice').text(totalPartsPrice.toFixed(2));
            
            var totalPartsPricePkr = 0;
            $('#parts_list tr').each(function() {
                var price = parseFloat($(this).find('input[name="part_price_pkr[]"]').val());
                if (!isNaN(price)) {
                    totalPartsPricePkr += price;
                }
            });
            $('#totalOilPartsPricePkr').text(totalPartsPricePkr.toFixed(2));
            
            var ex  = parseFloat($('#ex').val());
            var bl_amount =  parseFloat($('#bl_amount').val());
            var bl_amount_pkr = bl_amount * ex;
            
            $('#bl_amount_pkr').val(bl_amount_pkr.toFixed(2));

            var g_total = parseFloat(bl_amount + totalPartsPrice + totalCarPrice);
            var g_total_pkr = parseFloat(bl_amount_pkr + totalPartsPricePkr + totalCarPricePkr);

            $('#g_total').val(g_total.toFixed(2));
            $('#g_total_pkr').val(g_total_pkr.toFixed(2));

        }
      
        var part_id = 0; //

        function addParts() {
            // Get the last selected customer if any row exists
            if ($('#parts_list tr').length > 0) {
                lastSelectedCustomer = $('#parts_list tr:last select[name="customer[]"]').val();
            }

           part_id++; // Use timestamp for unique ID
            var options = '';

            // Use Array.prototype.map on the customers array
          
            var $newRow = $('<tr id="part_row_' + part_id + '">' +
                '<td><input type="text" name="part_desc[]" class="form-control"></td>' +
                '<td><input type="text" name="part_weight[]" required value="" class="form-control"></td>' +
                '<td><input type="text" name="part_grade[]" class="form-control"></td>' +
                '<td><input type="number" name="part_qty[]" id="part_qty_' + part_id + '"  required value="1" oninput="covertToPKRPart(' + part_id + ')")" class="form-control text-center"></td>' +
                '<td><input type="number" name="part_price[]" required step="any" id="part_price_' + part_id + '" oninput="covertToPKRPart(' + part_id + ')")" class="form-control text-center"></td>' +
                '<td><input type="number" name="part_price_pkr[]" required step="any" id="part_price_pkr_' + part_id + '" readonly class="form-control text-center"></td>' +
                '<input type="hidden" name="part_id[]" id="part_id_' + part_id + '" value="' + part_id + '">' +
                '</tr>');

            // Append the new row
            $("#parts_list").append($newRow);

            // Initialize selectize only for the new select element
            $('#part_customer_' + part_id).selectize({
                create: false,
                sortField: 'text'
            });
        }

        function covertToPKRPart(id) {
            var price = parseFloat($('#part_price_' + id).val());
            var qty = parseFloat($('#part_qty_' + id).val());
            var ex = parseFloat($('#ex').val());
         
            var price_pkr = price * ex;
            $('#part_price_pkr_' + id).val(price_pkr.toFixed(2));

            calculateTotal();
        }

        function deletePart(id) {
            $('#part_row_' + id).remove();
            calculateTotal();
        }

    </script>
@endsection
