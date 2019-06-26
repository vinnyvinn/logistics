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
            <div class="col-12">
                <div class="card card-outline-primary">
                    <div class="card-header">
                        <h4 class="card-title text-white">Active Jobs</h4>
                    </div>
                    <div class="card-body">
              
                        <div class="table-responsive">
                            <table id="dtforall" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>BL NO</th>
                                    <th>Name</th>
                                    <th>Contact Person</th>
                                    <th>Telephone</th>
                                    <th>Type</th>
                                    <th>Shipper</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                </thead>
                                <tbody id="customers">
                                @foreach($dms as $dm)
                                    @if($dm->status ==0)
                                        <tr>
                                        <td>{{ $dm->bl_number }}</td>
                                        <td>{{ $dm->customer ? ucwords($dm->customer->Name)  :''}}</td>
                                        <td>{{ $dm->customer ? ucwords($dm->customer->Contact_Person) : ''}}</td>
                                        <td>{{ $dm->customer ? $dm->customer->Telephone :''}}</td>
                                        {{--<td>--}}
{{--                                            @foreach($dm->contracts as $contract)--}}
{{--                                                {{ strtoupper($contract->company_name) }} | {{ $contract->contact }}--}}
                                                {{--@endforeach--}}
                                        {{--</td>--}}
                                        <td>{{ @ucwords($dm->quote->type ) }}</td>
                                        <td>{{ ucwords($dm->shipper ) }}</td>
                                        <td>{{ ucwords($dm->status == 0 ? 'In process' : 'Completed' ) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($dm->created_at)->format('d-M-y') }}</td>
                                        <td class="text-right">
                                       <a href=" {{ url('dms/edit/'. $dm->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-check"></i></a>

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
            <div class="col-12">
                <div class="card card-outline-success">
                    <div class="card-header">
                        <h4 class="card-title text-white">Completed Jobs</h4>
                    </div>
                    <div class="card-body">
                        
                        <div class="table-responsive">
                            <table id="dtforall2" class="table table-striped">
                                <thead>
                                <tr>
                                    <th>BL NO</th>
                                    <th>Name</th>
                                    <th>Contact Person</th>
                                    <th>Telephone</th>
                                    <th>Type</th>
                                    <th>Shipper</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                </thead>
                                <tbody id="customers">
                                @foreach($dms as $dm)
                                    @if($dm->status ==1)
                                        <tr>
                                            <td>{{ $dm->bl_number }}</td>
                                           <td>{{ $dm->customer ? ucwords($dm->customer->Name) :''}}</td>
                                            <td>{{ $dm->customer ? ucwords($dm->customer->Contact_Person) :''}}</td>
                                            <td>{{ $dm->customer ? $dm->customer->Telephone :''}}</td>
                                            <td>{{ ucwords($dm->quote ? $dm->quote->type :'' ) }}</td>
                                            <td>{{ ucwords($dm->shipper ) }}</td>
                                            <td>{{ ucwords($dm->status == 0 ? 'In process' : 'Completed' ) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($dm->created_at)->format('d-M-y') }}</td>
                                            <td class="text-right">

                                                  <a href=" {{ url('dms/edit/'. $dm->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-check"></i></a>

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
@endsection
