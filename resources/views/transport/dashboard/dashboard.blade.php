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
        @can('generate-quotation','view-quotation')
        <div class="row">
            <!-- Column -->
            <div class="col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <!-- Row -->
                        <div class="row">

                            <form action="{{ url('/generate-quotation')  }}">
                                <div class="row">
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            <select name="incoterm" required id="incoterm" class="form-control">
                                                <option value="">Select Incoterm</option>
                                                @foreach(\App\Incoterm::all() as $value)
                                                    <option value="{{$value->name}}">{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary">Generate Quotation</button>
                                    </div>

                                </div>

                            </form>
                            {{--<div class="col-sm-4 col-sm-offset-4">--}}
                                {{--<a href="{{ url('/generate-quotation/import') }}" class="btn btn-primary">Import Quotation</a>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
            {{--<div class="col-lg-3 col-md-6">--}}
                {{--<div class="card">--}}
                    {{--<div class="card-body">--}}

                        {{--<!-- Row -->                         <div class="row">--}}
                            {{--<div class="col-8"><h2>{{ count(\App\Quotation::all()) }} <i class="ti-angle-up font-14 text-success"></i></h2>--}}
                                {{--<h6>Quotations</h6></div>--}}
                            {{--<div class="col-4 align-self-center text-right p-l-0">--}}
                                {{--<div id="sparklinedash4"></div>--}}

                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}

            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <!-- Row -->
                        <div class="row">
                            <div class="col-8"><h2>{{ count(\App\BillOfLanding::where('status',1)->get()) }} <i class="ti-angle-up font-14 text-success"></i></h2>
                                <h6>Quotation completed</h6></div>
                            <div class="col-4 align-self-center text-right p-l-0">
                                <div id="sparklinedash4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column -->
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <!-- Row -->
                        <div class="row">
                            <div class="col-8"><h2>{{ count(\App\BillOfLanding::where('status',0)->get()) }} <i class="ti-angle-up font-14 text-success"></i></h2>
                                <h6>Quotation in process</h6></div>
                            <div class="col-4 align-self-center text-right p-l-0">
                                <div id="sparklinedash4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                                            {{--@if($quotation->status != 'converted' )--}}
                                                <tr>
                                                <td>{{ ucwords($quotation->customer ? $quotation->customer->Name : '') }}</td>
                                                <td>{{ ucfirst($quotation->customer ? $quotation->customer->Contact_Person : '') }}</td>
                                                <td>{{ $quotation->customer ? $quotation->customer->Telephone : '' }}</td>
                                                <td>{{ ucwords($quotation->status)}}</td>
                                                <td>{{ \Carbon\Carbon::parse($quotation->created_at)->format('d-M-y') }}</td>
                                                <td class="text-nowrap">
                                                    <a href=" {{ url('quotation/'. $quotation->id) }}" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                            {{--@endif--}}
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
        @endcan
    </div>
@endsection
