@extends('layouts.main')
@section('content')
    <div class="row page-titles m-b-0">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Dashboard</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <a href="{{ url()->previous() }}" class="btn btn-default">Back</a>
            </ol>
        </div>
        <div>
            <button class="right-side-toggle waves-effect waves-light btn-inverse btn btn-circle btn-sm pull-right m-l-10"><i class="ti-settings text-white"></i></button>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ ucwords($contract->company_name) }}'s Contract</h4>
                        <table class="table table-borded">
                            <tr>
                                <td><strong>Company Name : </strong> {{ ucwords($contract->company_name) }}</td>
                                <td><strong>Contact : </strong> {{ ucwords($contract->contact) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Contract Type : </strong> {{ $contract->contract_type }}</td>
                                <td><strong>Value : </strong> {{ $contract->value }}</td>
                            </tr>
                            <tr>
                                <td><strong>File : </strong> <a target="_blank" href="{{ asset($contract->remarks) }}">{{ $contract->remarks }}</a></td>
                                <td><strong>Address : </strong> {{ $contract->address }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>Contract Body : </strong> <br>
                                    {{ ucfirst($contract->body) }}</td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

