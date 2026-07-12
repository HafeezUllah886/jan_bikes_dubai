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
                            <label for="chassis_no">Chassis No.</label>
                            <select name="chassis_no[]" id="chassis_no" multiple class="select2">
                                <option value=""></option>
                                @foreach ($chassisNos as $chassisNo)
                                    <option value="{{ $chassisNo }}">{{ $chassisNo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="company">Company</label>
                            <select name="company[]" id="company" multiple class="select2">
                                <option value=""></option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company }}">{{ $company }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="model">Model</label>
                            <select name="model[]" id="model" multiple class="select2">
                                <option value=""></option>
                                @foreach ($models as $model)
                                    <option value="{{ $model }}">{{ $model }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="engine">Engine</label>
                            <select name="engine[]" id="engine" multiple class="select2">
                                <option value=""></option>
                                @foreach ($engines as $engine)
                                    <option value="{{ $engine }}">{{ $engine }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="inv_no">Inv No.</label>
                            <select name="inv_no[]" id="inv_no" multiple class="select2">
                                <option value=""></option>
                                @foreach ($inv_nos as $inv)
                                    <option value="{{ $inv }}">{{ $inv }}</option>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection
@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".select2").select2({
                placeholder: "Select options",
                allowClear: true
            });
        });
    </script>
@endsection
