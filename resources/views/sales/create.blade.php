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
                                <div class="col-6 d-flex flex-row-reverse"><a href="{{ route('sale.index') }}"
                                        class="btn btn-danger">Close</a></div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('sale.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <h4>Cars</h4>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <th width="200px">Customer</th>
                                            <th width="">Chassis</th>
                                            <th width="">Description</th>
                                            <th width="">Grade</th>
                                            <th width="" class="text-center">P-Price</th>
                                            <th width="" class="text-center">Expense</th>
                                            <th width="" class="text-center">Net Cost</th>
                                            <th width="" class="text-center">Sale Price</th>
                                            <th width="" class="text-center">Profit</th>
                                        </thead>
                                        <tbody id="cars_list">
                                            @foreach ($purchase->cars as $car)
                                                <tr id="car_{{ $car->id }}">
                                                    <td>
                                                        <select name="customer_id[]" id="customer_id" required class="form-control">  
                                                            <option value="">Select Customer</option>
                                                            @foreach ($customers as $customer)
                                                                <option value="{{ $customer->id }}">{{ $customer->title }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>{{ $car->chassis_no }}</td>
                                                    <td>{{ $car->maker }} | {{ $car->model }} | {{ $car->year }} | {{ $car->color }} | {{ $car->remarks }}</td>
                                                    <td>{{ $car->grade }}</td>
                                                    <td class="text-center"><input type="number" step="any" name="car_pprice[]" id="car_pprice_{{ $car->id }}" readonly value="{{ $car->price_pkr }}" class="form-control text-center"></td>
                                                    <td class="text-center"><input type="number" step="any" oninput="calculateCarNetCost({{ $car->id }})" name="car_expense[]" id="car_expense_{{ $car->id }}" value="0" class="form-control text-center"></td>
                                                    <td class="text-center"><input type="number" step="any" name="car_net_cost[]" id="car_net_cost_{{ $car->id }}" readonly value="{{ $car->price_pkr }}" class="form-control text-center"></td>
                                                    <td><input type="number" step="any" name="car_price[]" oninput="calculateCarNetCost({{ $car->id }})" id="car_price_{{ $car->id }}" value="0" class="form-control text-center"></td>
                                                    <td><input type="number" step="any" name="profit[]" oninput="calculateCarNetCost({{ $car->id }})" readonly id="profit_{{ $car->id }}" value="0" class="form-control text-center"></td>
                                                    <input type="hidden" name="car_id[]" value="{{ $car->id }}">
                                                </tr>
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end">Total</th>
                                                <th class="text-end" id="totalCarPPrice">{{ number_format($purchase->cars->sum('price_pkr'), 0) }}</th>
                                                <th class="text-end" id="totalCarExpense">0.00</th>
                                                <th class="text-end" id="totalCarNetCost">{{ number_format($purchase->cars->sum('price_pkr'), 0) }}</th>
                                                <th class="text-end" id="totalCarPrice">0.00</th>
                                                <th class="text-end" id="totalCarProfit">0.00</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-6">
                                <h4>Parts / Engine Oil</h4>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <th width="200px">Customer</th>
                                            <th width="">Description</th>
                                            <th width="">Grade</th>
                                            <th width="">Qty</th>
                                            <th width="" class="text-center">P-Price</th>
                                            <th width="" class="text-center">Expense</th>
                                            <th width="" class="text-center">Net Cost</th>
                                            <th width="" class="text-center">Sale Price</th>
                                            <th width="" class="text-center">Profit</th>
                                        </thead>
                                        <tbody id="parts_list">
                                            @foreach ($purchase->parts as $part)
                                                <tr>
                                                    <td>
                                                        <select name="part_customer_id[]" id="customer_id" required class="form-control">  
                                                            <option value="">Select Customer</option>
                                                            @foreach ($customers as $customer)
                                                                <option value="{{ $customer->id }}">{{ $customer->title }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>{{ $part->description }} | {{$part->weight_ltr}}</td>
                                                    <td>{{ $part->grade }}</td>
                                                    <td>{{ $part->qty }}</td>
                                                    <td class="text-center"><input type="number" step="any" name="part_pprice[]" id="part_pprice_{{ $part->id }}" readonly value="{{ $part->price_pkr }}" class="form-control text-center"></td>
                                                    <td class="text-center"><input type="number" step="any" name="part_expense[]" oninput="calculatePartsNetCost({{ $part->id }})" id="part_expense_{{ $part->id }}" value="0" class="form-control text-center"></td>
                                                    <td class="text-center"><input type="number" step="any" name="part_net_cost[]" id="part_net_cost_{{ $part->id }}" readonly value="{{ $part->price_pkr }}" class="form-control text-center"></td>
                                                    <td><input type="number" step="any" name="part_price[]" id="part_price_{{ $part->id }}" oninput="calculatePartsNetCost({{ $part->id }})" value="0" class="form-control text-center"></td>
                                                    <td><input type="number" step="any" name="part_profit[]" id="part_profit_{{ $part->id }}" readonly oninput="calculatePartsNetCost({{ $part->id }})" value="0" class="form-control text-center"></td>
                                                    <input type="hidden" name="part_id[]" value="{{ $part->id }}">
                                                </tr>
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end">Total</th>
                                                <th class="text-end" id="totalPartsPPrice">{{ number_format($purchase->parts->sum('price_pkr'), 0) }}</th>
                                                <th class="text-end" id="totalPartsExpense">0.00</th>
                                                <th class="text-end" id="totalPartsNetCost">{{ number_format($purchase->parts->sum('price_pkr'), 0) }}</th>
                                                <th class="text-end" id="totalPartsPrice">0.00</th>
                                                <th class="text-end" id="totalPartsProfit">0.00</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-2">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Container #</p>
                                    <h5 class="fs-14 mb-0">{{$purchase->c_no}}</h5>
                                    <input type="hidden" name="purchase_id" value="{{$purchase->id}}">
                                </div>
                                <div class="col-2">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">BL #</p>
                                    <h5 class="fs-14 mb-0">{{$purchase->bl_no}}</h5>
                                </div>
                                <div class="col-2">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Total Cost</p>
                                    <h5 class="fs-14 mb-0"> <span id="totalCost">{{number_format($purchase->net_amount, 0)}}</span> </h5>
                                </div>
                                <div class="col-2">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Total Sale</p>
                                    <h5 class="fs-14 mb-0"><span id="totalSalePrice">0</span></h5>
                                    <input type="hidden" name="total" id="total_amount">
                                </div>
                                <div class="col-2">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold">Profit / Loose</p>
                                    <h5 class="fs-14 mb-0" id="profit_loose">0</h5>
                                </div>
                            </div>
                        </div>

                       <div class="row">
                        <div class="col-3 mt-2">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" required name="date"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-6 mt-2">
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <input type="text" class="form-control" name="notes">
                            </div>
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
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
      
        $(document).ready(function() {
            // Initialize any existing select elements on page load
            $('select.selectize').selectize();
        });
       
        function calculateCarNetCost(id) {
            var price = parseFloat($('#car_pprice_' + id).val());
            var expense = parseFloat($('#car_expense_' + id).val());
            var net_cost = price + expense;
            var sale_price = parseFloat($('#car_price_' + id).val());
            var profit = sale_price - net_cost;
            $('#car_net_cost_' + id).val(net_cost.toFixed(2));
            $('#profit_' + id).val(profit.toFixed(2));
            calculateTotal();
        }

         function calculatePartsNetCost(id) {
            var price = parseFloat($('#part_pprice_' + id).val());
            var expense = parseFloat($('#part_expense_' + id).val());
            var net_cost = price + expense;
            var sale_price = parseFloat($('#part_price_' + id).val());
            var profit = sale_price - net_cost;
            $('#part_net_cost_' + id).val(net_cost.toFixed(2));
            $('#part_profit_' + id).val(profit.toFixed(2));
            calculateTotal();
        }

          function calculateTotal() {

            var totalCarExpense = 0;
            $('#cars_list tr').each(function() {
                var price = parseFloat($(this).find('input[name="car_expense[]"]').val());
                if (!isNaN(price)) {
                    totalCarExpense += price;
                }
            });
            $('#totalCarExpense').text(totalCarExpense.toFixed(2));
            

            var totalCarCost = 0;
            $('#cars_list tr').each(function() {
                var cost = parseFloat($(this).find('input[name="car_net_cost[]"]').val());
                if (!isNaN(cost)) {
                    totalCarCost += cost;
                }
            });
            $('#totalCarNetCost').text(totalCarCost.toFixed(2));

            var totalCarPrice = 0;
            $('#cars_list tr').each(function() {
                var price = parseFloat($(this).find('input[name="car_price[]"]').val());
                if (!isNaN(price)) {
                    totalCarPrice += price;
                }
            });
            $('#totalCarPrice').text(totalCarPrice.toFixed(2));
            
            var totalPartsExpense = 0;
            $('#parts_list tr').each(function() {
                var expense = parseFloat($(this).find('input[name="part_expense[]"]').val());
                if (!isNaN(expense)) {
                    totalPartsExpense += expense;
                }
            });
            $('#totalPartsExpense').text(totalPartsExpense.toFixed(2));
            
            var totalcarProfit = 0;
            $('#cars_list tr').each(function() {
                var profit = parseFloat($(this).find('input[name="profit[]"]').val());
                if (!isNaN(profit)) {
                    totalcarProfit += profit;
                }
            });
            $('#totalCarProfit').text(totalcarProfit.toFixed(2));
            
            var totalPartsCost = 0;
            $('#parts_list tr').each(function() {
                var cost = parseFloat($(this).find('input[name="part_net_cost[]"]').val());
                if (!isNaN(cost)) {
                    totalPartsCost += cost;
                }
            });
            $('#totalPartsNetCost').text(totalPartsCost.toFixed(2));

            var totalPartsPrice = 0;
            $('#parts_list tr').each(function() {
                var price = parseFloat($(this).find('input[name="part_price[]"]').val());
                if (!isNaN(price)) {
                    totalPartsPrice += price;
                }
            });
            $('#totalPartsPrice').text(totalPartsPrice.toFixed(2));
            
            var totalPartsProfit = 0;
            $('#parts_list tr').each(function() {
                var price = parseFloat($(this).find('input[name="part_profit[]"]').val());
                if (!isNaN(price)) {
                    totalPartsProfit += price;
                }
            });
            $('#totalPartsProfit').text(totalPartsProfit.toFixed(2));
            
        
            var totalCost = parseFloat(totalCarCost + totalPartsCost);
            var totalSalePrice = parseFloat(totalPartsPrice + totalCarPrice);

            $('#totalCost').text(totalCost.toFixed(2));
            $('#totalSalePrice').text(totalSalePrice.toFixed(2));

            var profit = parseFloat(totalSalePrice - totalCost);
            $('#profit_loose').text(profit.toFixed(2));

        }
      
    </script>
@endsection
