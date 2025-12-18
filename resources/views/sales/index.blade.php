@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <form>
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">From</span>
                            <input type="date" class="form-control" placeholder="Username" name="from" value="{{$from}}" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">To</span>
                            <input type="date" class="form-control" placeholder="Username" name="to" value="{{$to}}" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                    </div>
                    <div class="col-md-2">
                       <input type="submit" value="Filter" class="btn btn-success w-100">
                    </div>
                </div>
            </form>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Sales</h3>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Create Sale</button>
                </div>
                <div class="card-body">
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
                            <th>Sale #</th>
                            <th>C/No</th>
                            <th>BL No</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach ($sales as $key => $sale)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $sale->id }}</td>
                                    <td>{{ $sale->purchase->c_no }}</td>
                                    <td>{{ $sale->purchase->bl_no }}</td>
                                    <td>{{ date('d M Y', strtotime($sale->date)) }}</td>
                                    <td>{{ number_format($sale->amount) }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button class="dropdown-item" onclick="newWindow('{{route('sale.show', $sale->id)}}')"
                                                        onclick=""><i
                                                            class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                        View
                                                    </button>
                                                </li>
                                               
                                                <li>
                                                    <a class="dropdown-item" onclick="newWindow('{{route('sale.edit', $sale->id)}}')">
                                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                        Edit
                                                    </a>
                                                </li>
                                               
                                               {{--  <li>
                                                    <a class="dropdown-item text-danger" href="{{route('export.delete', $export->id)}}">
                                                        <i class="ri-delete-bin-2-fill align-bottom me-2 text-danger"></i>
                                                        Delete
                                                    </a>
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- Default Modals -->

    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create Sale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sale.create') }}" method="get">
                <div class="modal-body">
                  
                        @csrf
                        <div class="form-group">
                            <label for="purchase_id">Purchase</label>
                            <select name="purchase_id" id="purchase_id" required class="form-control">
                                @foreach ($purchases as $purchase)
                                    <option value="{{ $purchase->id }}">{{ $purchase->c_no }} | {{ $purchase->bl_no }}</option>
                                @endforeach
                            </select>
                        </div>
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection

@section('page-css')
<link rel="stylesheet" href="{{ asset('assets/libs/datatable/datatable.bootstrap5.min.css') }}" />
<!--datatable responsive css-->
<link rel="stylesheet" href="{{ asset('assets/libs/datatable/responsive.bootstrap.min.css') }}" />

<link rel="stylesheet" href="{{ asset('assets/libs/datatable/buttons.dataTables.min.css') }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.print.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/vfs_fonts.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/pdfmake.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatable/jszip.min.js')}}"></script>

    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
@endsection
