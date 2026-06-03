@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <form>
                <div class="row">
                    <div class="col-md-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">From</span>
                            <input type="date" class="form-control" placeholder="Username" name="start"
                                value="{{ $start }}" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">To</span>
                            <input type="date" class="form-control" placeholder="Username" name="end"
                                value="{{ $end }}" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Status</span>
                            <select name="status" id="status1" class="form-control">
                                <option value="all">All</option>
                                <option value="Available" {{ $status == 'Available' ? 'selected' : '' }}>Available</option>
                                <option value="Sold" {{ $status == 'Sold' ? 'selected' : '' }}>Sold</option>
                                <option value="Booked" {{ $status == 'Booked' ? 'selected' : '' }}>Booked</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Inv No</span>
                            <select name="inv_no" id="inv_no" class="form-control">
                                <option value="">Select Inv No</option>
                                @foreach ($invoices as $invoice)
                                    <option value="{{ $invoice->inv_no }}"
                                        {{ $invoice->inv_no == $inv_no ? 'selected' : '' }}>
                                        {{ $invoice->inv_no }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="submit" value="Filter" class="btn btn-success w-100">
                    </div>
                </div>
            </form>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Purchases</h3>
                    <div>
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addExpenses">Add
                            Expenses / Profit</button>

                        <a href="{{ route('purchase.create') }}" type="button" class="btn btn-primary ">Create
                            New</a>
                    </div>

                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <table class="table" id="buttons-datatables">
                        <thead>
                            <th>#</th>
                            <th>Date</th>
                            <th>Inv No.</th>
                            <th>Type</th>
                            <th>Chassis No.</th>
                            <th>Engine No.</th>
                            <th>Description</th>
                            <th>Net Cost</th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $key => $purchase)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ date('d M Y', strtotime($purchase->date)) }}</td>
                                    <td>{{ $purchase->inv_no }}</td>
                                    <td>{{ $purchase->type }}</td>
                                    <td>{{ $purchase->chassis }}</td>
                                    <td>{{ $purchase->engine }}</td>
                                    <td>{{ $purchase->model }} | {{ $purchase->company }} | {{ $purchase->color }} </td>
                                    <td>{{ number_format($purchase->costWithExpenseProfit(), 2) }}</td>
                                    <td>{{ $purchase->status }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button class="dropdown-item"
                                                        onclick="newWindow('{{ route('purchase.show', $purchase->id) }}')"
                                                        type="button"><i
                                                            class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                        View
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item view-expense-profit" type="button"
                                                        data-purchase-id="{{ $purchase->id }}">
                                                        <i class="ri-file-list-3-line align-bottom me-2 text-muted"></i>
                                                        Expense/Profit
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7" class="text-end">Total</td>
                                <td>{{ $purchases->sum('total') }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>

                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade zoomIn" id="addExpenses" tabindex="-1" aria-labelledby="addExpensesLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addExpensesLabel">Add Expense / Profit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('purchase.storeExpenseProfit') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="import_id">Select Import</label>
                                    <select name="import_id" id="import_id" class="form-control">
                                        <option value="">Select Import</option>
                                        @foreach ($imports as $import)
                                            <option value="{{ $import->id }}">{{ $import->inv_no }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="expense">Expense</option>
                                        <option value="profit">Profit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="amount">Bike Amount</label>
                                    <input type="number" name="bike_amount" id="bike_amount"
                                        value="{{ old('bike_amount') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="amount">Car Amount</label>
                                    <input type="number" name="car_amount" id="car_amount"
                                        value="{{ old('car_amount') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="amount">Parts Amount</label>
                                    <input type="number" name="parts_amount" id="parts_amount"
                                        value="{{ old('parts_amount') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" id="date"
                                        value="{{ old('date', date('Y-m-d')) }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="expenseProfitModal" tabindex="-1" aria-labelledby="expenseProfitModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="expenseProfitModalLabel">Purchase Expense / Profit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-0">
                    <div class="text-center py-5">
                        Loading expense/profit details...
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Default Modals -->
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.view-expense-profit').forEach(function(button) {
                button.addEventListener('click', function() {
                    var purchaseId = this.dataset.purchaseId;
                    var url = '/purchase/' + purchaseId + '/expense-profit';
                    var modalBody = document.querySelector('#expenseProfitModal .modal-body');
                    modalBody.innerHTML =
                        '<div class="text-center py-5">Loading expense/profit details...</div>';

                    fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(response) {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.text();
                        })
                        .then(function(html) {
                            modalBody.innerHTML = html;
                            var expenseProfitModal = new bootstrap.Modal(document
                                .getElementById('expenseProfitModal'));
                            expenseProfitModal.show();
                        })
                        .catch(function() {
                            modalBody.innerHTML =
                                '<div class="alert alert-danger">Unable to load expense/profit details.</div>';
                        });
                });
            });
        });
    </script>
@endsection
