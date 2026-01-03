@extends('layout.popups')
@section('content')
        <div class="row justify-content-center">
            <div class="col-xxl-9">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                                <a href="{{ route('purchase.create') }}" class="btn btn-primary ml-4">Create New</a>
                                <a href="javascript:window.print()" class="btn btn-success ml-4"><i class="ri-printer-line mr-4"></i> Print</a>
                            </div>
                            <div class="card-header border-bottom-dashed p-4">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h1>{{projectNameHeader()}}</h1>
                                    </div>
                                    <div class="flex-shrink-0 mt-sm-0 mt-3">
                                        <h3>Purchase Vouchar</h3>
                                    </div>
                                </div>
                            </div>
                            <!--end card-header-->
                        </div><!--end col-->
                        <div class="col-lg-12 ">
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Date</p>
                                        <h5 class="fs-14 mb-0">{{date("d M Y", strtotime($purchase->date))}}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">BL No.</p>
                                        <h5 class="fs-14 mb-0">{{$purchase->bl_no}}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Container No.</p>
                                        <h5 class="fs-14 mb-0">{{$purchase->c_no}}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Ex Rate</p>
                                        <h5 class="fs-14 mb-0">{{number_format($purchase->conversion_rate, 3)}}</h5>
                                    </div>
                                    <div class="col-12 mt-1">
                                        <div class="card-header">
                                            <h4>Cars</h4>
                                        </div>
                                        <table class="table table-bordered">
                                             <thead>
                                                 <tr class="table-active">
                                                     <th class='no-padding'>Model</th>
                                                     <th class='no-padding'>Maker</th>
                                                     <th class='no-padding'>Chassis No.</th>
                                                     <th class='no-padding'>Auction</th>
                                                     <th class='no-padding'>Year</th>
                                                     <th class='no-padding'>Color</th>
                                                     <th class='no-padding'>Grade</th>
                                                     <th class='no-padding'>Price</th>
                                                     <th class='no-padding'>Price PKR</th>
                                                     <th class='no-padding'>Remarks</th>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 @foreach($purchase->cars as $car)
                                                 <tr>
                                                     <td class='no-padding'>{{$car->model}}</td>
                                                     <td class='no-padding'>{{$car->maker}}</td>
                                                     <td class='no-padding'>{{$car->chassis_no}}</td>
                                                     <td class='no-padding'>{{$car->auction}}</td>
                                                     <td class='no-padding'>{{$car->year}}</td>
                                                     <td class='no-padding'>{{$car->color}}</td>
                                                     <td class='no-padding'>{{$car->grade}}</td>
                                                     <td class="text-end no-padding">{{number_format($car->price)}}</td>
                                                     <td class="text-end no-padding">{{number_format($car->price_pkr)}}</td>
                                                     <td class='no-padding'>{{$car->remarks}}</td>
                                                 </tr>
                                                 @endforeach
                                             </tbody>
                                             <tfoot>
                                                 <tr class="table-active">
                                                     <th colspan="7" class="text-end no-padding">Total</th>
                                                     <th class="text-end no-padding">{{number_format($purchase->cars->sum('price'))}}</th>
                                                     <th class="text-end no-padding">{{number_format($purchase->cars->sum('price_pkr'))}}</th>
                                                     <th ></th>
                                                 </tr>
                                             </tfoot>
                                        </table>
                                     </div>
                                    <div class="col-12 mt-1">
                                        <div class="card-header">
                                            <h4>Parts / Oil</h4>
                                        </div>
                                       <table class="table table-bordered">
                                            <thead>
                                                <tr class="table-active">
                                                    <th class='no-padding'>Description</th>
                                                    <th class='no-padding'>Weight(Ltr)</th>
                                                    <th class='no-padding'>Grade</th>
                                                    <th class='no-padding'>Qty</th>
                                                    <th class='no-padding'>Price</th>
                                                    <th class='no-padding'>Price PKR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($purchase->parts as $part)
                                                <tr>
                                                    <td class='no-padding'>{{$part->description}}</td>
                                                    <td class='no-padding'>{{$part->weight_ltr}}</td>
                                                    <td class='no-padding'>{{$part->grade}}</td>
                                                    <td class="text-end no-padding">{{number_format($part->qty)}}</td>
                                                    <td class="text-end no-padding">{{number_format($part->price)}}</td>
                                                    <td class="text-end no-padding">{{number_format($part->price_pkr)}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-active">
                                                    <th colspan="3" class="text-end no-padding">Total</th>
                                                    <th class="text-end no-padding">{{number_format($purchase->parts->sum('qty'))}}</th>
                                                    <th class="text-end no-padding">{{number_format($purchase->parts->sum('price'))}}</th>
                                                    <th class="text-end no-padding">{{number_format($purchase->parts->sum('price_pkr'))}}</th>
                                                </tr>
                                            </tfoot>
                                       </table>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Cars Total</p>
                                        <h5 class="fs-14 mb-0">{{number_format($purchase->cars->sum('price'))}} | PKR {{number_format($purchase->cars->sum('price_pkr'))}}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Parts / Oil Total</p>
                                        <h5 class="fs-14 mb-0">{{number_format($purchase->parts->sum('price'))}} | PKR {{number_format($purchase->parts->sum('price_pkr'))}}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">BL Amount</p>
                                        <h5 class="fs-14 mb-0">{{number_format($purchase->bl_amount)}} | PKR {{number_format($purchase->bl_amount_pkr)}}</h5>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <p class="text-muted mb-2 text-uppercase fw-semibold">Grand Total</p>
                                        <h5 class="fs-14 mb-0">{{number_format($purchase->cars->sum('price') + $purchase->parts->sum('price') + $purchase->bl_amount)}} | PKR {{number_format($purchase->cars->sum('price_pkr') + $purchase->parts->sum('price_pkr') + $purchase->bl_amount_pkr)}}</h5>
                                    </div>
                                   
                                   
                                </div>
                                <!--end row-->
                            </div>
                            
                            <!--end card-body-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->

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

