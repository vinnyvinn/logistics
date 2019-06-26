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
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add Service</h4>
                        <form class="form-material m-t-40" action="{{ route('services.store') }}" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="type">Service Incoterm</label>
                                        <select name="type" required id="type" class="form-control">
                                            <option value="">Select Incoterm</option>
                                            <option value="All">All Incoterms</option>
                                            @foreach(\App\Incoterm::all() as $value)
                                                <option value="{{$value->name}}">{{$value->name}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="type">Service Type</label>
                                        <select name="fixed" required id="fixed" class="form-control">
                                            <option value="">Select</option>
                                            <option value="kpa">KPA</option>
                                            <option value="internal">Internal</option>
                                            <option value="external">External</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Service Name </label>
                                        <input type="text" required id="name" name="name" class="form-control" placeholder="Service Name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="unit">Service Unit</label>
                                        <input type="text" required id="unit" name="unit" class="form-control" placeholder="Service Unit">
                                    </div>
                                    <div class="form-group">
                                        <label for="rate">Rate Per Unit </label>
                                        <input type="text" required id="rate" name="rate" class="form-control" placeholder="Rate Per Unit">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn pull-right btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function subCat() {
            var conceptName = $('#type').find(":selected").text();
            if (conceptName == "Import"){
                $('#subcat').empty().append(
                    '<option value="">Select Service Type Sub Category</option>' +
                    '<option value="Import for home use">Import for home use</option>' +
                    '<option value="Transit">Transit</option>' +
                    '<option value="Transhipment">Transhipment</option>'+
                    '<option value="Temporary Import">Temporary Import</option>'
                );
            }
            else {
                $('#subcat').empty().append(
                    '<option value="">Select Service Type Sub Category</option>' +
                    '<option value="Export home produce">Export home produce</option>' +
                    '<option value="Re Export">Re Export</option>' +
                    '<option value="Transit Outward">Transit Outward</option>'+
                    '<option value="Temporary Exportation">Temporary Exportation</option>'
                );
            }
        }
    </script>
    @endsection
