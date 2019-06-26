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
                        <form class="form-material m-t-40" action="{{ route('services.update', $service) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('put') }}
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{--<<<<<<< HEAD--}}
                                        <label for="type">Service Incoterm</label>
                                        <select name="type" required id="type" class="form-control">
                                            <option value="">Select Incoterm</option>
                                            <option {{ $service->type == 'All' ? 'selected' : ''}} value="All">All Incoterms</option>
                                            @foreach(\App\Incoterm::all() as $value)
                                                <option {{ $service->type == $value->name ? 'selected' : ''}} value="{{$value->name}}">{{$value->name}}</option>
                                            @endforeach

                                            {{--=======--}}
                                            {{--<label for="type">Service Type</label>--}}
                                            {{--<select name="type" required id="type" onchange="subCat()" class="form-control">--}}
                                            {{--<option value="">Select Service Type</option>--}}
                                            {{--<option value="{{ \Esl\helpers\Constants::TRANSPORT_IMPORT }}">Import</option>--}}
                                            {{--<option value="{{ \Esl\helpers\Constants::TRANSPORT_EXPORT }}">Export</option>--}}
                                            {{--                                            <option value="{{ \Esl\helpers\Constants::TRANSPORT_CLEARING }}">Clearing</option>--}}
                                            {{--                                            <option value="{{ \Esl\helpers\Constants::TRANSPORT_ALL }}">General</option>--}}
                                            {{-->>>>>>> 8fcd5cf13774b6320143531274c0f8f367a1ed49--}}
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="type">Service Type</label>
                                        <select name="fixed" required id="fixed" class="form-control">
                                            <option value="">Select</option>
                                            <option {{ $service->fixed == 'kpa' ? 'selected' : ''}} value="kpa">KPA</option>
                                            <option {{ $service->fixed == 'internal' ? 'selected' : ''}} value="internal">Internal</option>
                                            <option {{ $service->fixed == 'external' ? 'selected' : ''}} value="external">External</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Service Name </label>
                                        <input type="text" required value="{{ $service->name }}" id="name" name="name" class="form-control" placeholder="Service Name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="unit">Service Unit</label>
                                        <input type="text" required value="{{ $service->unit }}" id="unit" name="unit" class="form-control" placeholder="Service Unit">
                                    </div>
                                    <div class="form-group">
                                        <label for="rate">Rate Per Unit </label>
                                        <input type="text" required value="{{ $service->rate }}" id="rate" name="rate" class="form-control" placeholder="Rate Per Unit">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn pull-right btn-primary">Update</button>
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
