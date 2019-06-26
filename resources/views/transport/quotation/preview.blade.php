@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="card card-body printableArea">
                <br>
                <div class="row">
                    @include('partials.quotation.invoice-head')
                    <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>DESCRIPTION</th>
                                            <th class="text-right">QUANTITY</th>
                                            <th class="text-right">UNIT PRICE</th>
                                            <th class="text-right">TAX</th>
                                            <th class="text-right">TOTAL AMOUNT</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($quotation->services as $service)
                                            <tr id="{{$service->id}}">
                                                <td> {{ ucwords($service->name) }} </td>
                                                <td class="text-right">{{$service->total_units}} </td>
                                                <td class="text-right">{{ number_format($service->selling_price) }}</td>
                                                <td class="text-right">{{ number_format($service->tax) }}</td>
                                                <td class="text-right">{{ number_format($service->total) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="pull-right m-t-30 text-right">
                            <div class="col-sm-12">
                                <h3 id="total_amount"><b>Total (Excl) {{ $currency }} :</b> {{ number_format($quotation->services->sum('total'), 2) }}</h3>
                                <h4 id="total_amount"><b>Total Tax {{ $currency }} :</b> {{ number_format($quotation->services->sum('tax'), 2) }}</h4>
                                <h3 id="total_amount"><b>Total (Incl) {{ $currency }} :</b> {{ number_format($quotation->services->sum('total'), 2) }}</h3>                            </div>
                            <hr>

                        </div>
                        <div>
                            <address id="client_details text-left">
                                <p>
                                    <br><b>Prepared by :</b> {{ ucwords($quotation->user->name)  }}</p>
                               <p><b>Checked by :</b> {{ $quotation->checkedBy == null ? '................................' : ucwords($quotation->checkedBy->name )}}</p>
                                <p><b>Approved by :</b> {{ $quotation->approvedBy == null ? '................................' : ucwords($quotation->approvedBy->name) }}</p>

                                <p><b>Date :</b> {{ \Carbon\Carbon::now()->format('d-M-y') }}</p>
                                {{--<h4><b>Signed :</b> ...........................</h4>--}}
                            </address>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-sm-12">
                        <hr>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="text-center">Sovereign Logistics &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Kenya Limited &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            Moi Avenue</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-12 text-center"><h4 class="text-right"> Commercial Bank of Africa, Moi Avenue Branch
                                            <br>
                                            Account Details <br>{{ $currency }} Account : {{ $currency == 'USD' ? '6807430028' : '6807430012'}}
                                        </h4>
                                        <h4 style="text-align: left !important;">PAYMENT TERMS: INVOICE DUE ON DEMAND <br>
                                            ANY OVERDUE AMOUNT WILL ATTRACT 3% INTEREST PER MONTH <br>ALL TRANSACTIONS ARE GOVERNED BY OUR STANDARD TRADING CONDITIONS AVAILABLE UPON REQUEST</h4> <h4 class="text-center">YOUR DISTINCTIVE LOGISTICS PARTNER</h4></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <h4>Required Documents</h4>
                    </div>
                    <div class="col-sm-12">
                        <table class="table table-strpped">
                            <tr>
                                <th>Document Name</th>
                                <th>Description</th>
                                {{--<th class="text-right">Action</th>--}}
                            </tr>
                            <tbody>
                            @if($quotation->doc_ids != null)
                            @foreach(json_decode($quotation->doc_ids) as $docs)
                                <tr>
                                    <td>{{ ucwords($docs->name) }}</td>
                                    <td>{{ ucfirst($docs->description) }}</td>
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-4 pull-right">
                        @if($quotation->status == \Esl\helpers\Constants::LEAD_QUOTATION_WAITING || $quotation->status == \Esl\helpers\Constants::LEAD_QUOTATION_APPROVED )
                            <a href="{{ url('/quotation/customer/accepted/'.$quotation->id) }}" onclick="cssLoader()" class="btn btn btn-primary">Accept</a>
                            <a href="{{ url('/quotation/customer/declined/'.$quotation->id) }}" onclick="cssLoader()" class="btn btn-danger" type="submit"> Decline </a>
                        @endif
                            <button id="print" class="btn btn-success" type="button"> <span><i class="fa fa-print"></i> Print</span> </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection