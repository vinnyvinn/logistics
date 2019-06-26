@extends('layouts.main')
@section('content')
    <div class="row page-titles m-b-0">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Dashboard</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
        <div>
            <button class="right-side-toggle waves-effect waves-light btn-inverse btn btn-circle btn-sm pull-right m-l-10"><i class="ti-settings text-white"></i></button>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Quotations In Process</h4>
                   
                    <div class="comment-widgets m-b-20">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table id="dtforall" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Contact Person</th>
                                            <th>Telephone</th>
                                            <th>Status</th>
                                            <th>Generate On</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="customers">
                                        @foreach($quotations as $quotation)
                                            @if($quotation->status != 'converted')
                                            <tr>
                                                <td>{{ @ucwords($quotation->customer ? $quotation->customer->Name : '') }}</td>
                                                <td>{{ @ucfirst($quotation->customer ? $quotation->customer->Contact_Person : '') }}</td>
                                                <td>{{ @$quotation->customer ? $quotation->customer->Telephone : ''}}</td>
                                                <td>{{ @ucwords($quotation->status)}}</td>
                                                <td>{{ \Carbon\Carbon::parse($quotation->created_at)->format('d-M-y') }}</td>
                                                <td class="text-nowrap">
                                                    <a href=" {{ url('quotation/'. $quotation->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                     </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Completed</h4>
                   
                    <div class="comment-widgets m-b-20">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table id="dtforall2" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Contact Person</th>
                                            <th>Telephone</th>
                                            <th>Status</th>
                                            <th>Generate On</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="customers">
                                        @foreach($quotations as $quotation)
                                            @if($quotation->status == 'converted')
                                            <tr>
                                                <td>{{ @ucwords($quotation->customer->Name) }}</td>
                                                <td>{{ @ucfirst($quotation->customer->Contact_Person) }}</td>
                                                <td>{{ @$quotation->customer->Telephone }}</td>
                                                <td>{{ @ucwords($quotation->status)}}</td>
                                                <td>{{ \Carbon\Carbon::parse($quotation->created_at)->format('d-M-y') }}</td>
                                                <td class="text-nowrap">
                                                    <a href=" {{ url('quotation/'. $quotation->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
@endsection
