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
            <div class="card card-body printableArea">
                <h3 class="text-center">Quotation</h3>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-left">
                            <address>
                                <img src="{{ asset('images/logo.png') }}" alt="">
                                {{--<h4>Express Shipping & Logistics (EA) Limited</h4>--}}
{{--                                <img src="{{ asset('images/logo.png') }}" alt="">--}}
                                <p style="font-size: smaller">Powering Our Customers to be Leaders in their Markets</p>
                                <h4>CANNON TOWERS 6TH FLOOR, MOI AVENUE <br>
                                    MOMBASA</h4>
                            </address>
                        </div>
                        <div class="pull-right">
                            <h4><b>Customer Details</b></h4>
                            <h4 class="inputcurrency">Choose Currency <button onclick="currencyChangeUSD()" class="inputcurrency btn btn-sm btn-info">USD</button>
                                <button onclick="currencyChangeKES()" class="inputcurrency btn btn-sm btn-warning">KES</button></h4>
                            <div id="add_customer" class="col-sm-12">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="customer"><h4>Add Customer</h4></label>
                                        <input type="text" id="customer" class="form-control" placeholder="Search customer">
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="display"></div>
                                </div>
                            </div>
                            <address id="client_details">
                                <br>
                                <p><b>Date : </b> {{ \Carbon\Carbon::now()->format('d-M-y') }}</p>
                            </address>
                        </div>
                    </div>
                    <hr>

                    <div class="col-sm-12 table-responsive m-t-40">
                        <form id="cargo_details" action="" onsubmit="event.preventDefault(); addCargo(this)">
                            <h4>Client Details</h4>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group"><label for="">B/L NO</label>
                                        <input type="text" required name="bl_no" id="bl_no" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group"><label for="">Cargo</label>
                                        <input type="text" required name="cargo_name" id="cargo_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group"><label for="">Vessel</label>
                                        <input type="text" required name="vessel_name" id="vessel_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group"><label for="">Cargo Quantity</label>
                                        <input type="text" required name="cargo_qty" id="cargo_qty" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group"><label for="">Container No</label>
                                        <input type="text" required name="container_no" id="container_no" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group"><label for="">Consignee</label>
                                        <input type="text" required name="consignee_name" id="consignee_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <button class="btn btn-primary pull-right">Add Details</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <input type="hidden" name="typo" value="{{$type}}" class="typo" walla="{{$type}}">
                    <div class="col-md-12">
                        <div class="table-responsive m-t-40" style="clear: both;">
                            @include('partials.quotation.add-service')
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="pull-right m-t-30 text-right">
                            <div class="col-sm-12">
                                <h3 id="total_amount"><b id="iccurenyc">Total :</b> 0</h3>
                            </div>
                            <div class="row">
                                <button onclick="storeServiceData(); cssLoader()" class="btn btn-block btn-primary">Save</button>
                            </div>
                        </div>
                        <div class="pull-left m-t-30 text-left">
                            <div class="col-sm-12">
                                <h3 id="total_amount"><b id="iccurenyc">Total :</b> 0</h3>
                            </div>
                            <div class="row">
                                <button onclick="reloadForm()" class="btn btn-block btn-danger">Reset</button>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>


    var urlParams = new URLSearchParams(window.location.search);

        var search_item = $('#customer');
        var display_search = $('#display');
        var data = {
            'currency' : '',
            'inputCur' : '',
            'exrate' : '{{ $exrate }}',
            'DCLink':'',
            'type':$('.typo').val(),
            'services':{},
            'cargo_details':{}
        }
        
        search_item.on('keyup', function () {

            if(search_item.val() == "") {
                display_search.html("")
            }
            else {
                axios.post('/search-customer', {'search_item' : search_item.val()})
                    .then( function (response) {
                        display_search.empty().append(response.data.output);
                    })
                    .catch( function (response) {
                        console.log(response.data);
                    });
            }
        });

        function addService() {
            var  service_selling = 0;
            var  checkit = 0;

            if($('#service').val() === "" || $('#service').val() === null){
                alert('Select One Service');
                return true;
            }

            if($('#tax').val() === "" || $('#tax').val() === null){
                alert('Select Tax');
                return true;
            }

            var selected = document.getElementById("service")
            var getSelectedService = JSON.parse(selected.options[selected.selectedIndex].value);

            var sTax = document.getElementById("tax");
            var selectedTax = JSON.parse(sTax.options[sTax.selectedIndex].value);


            var service_units = $('#service_units').val();

            service_selling= $('#selling_price').val();

            checkit = this.data.inputCur == 'USD' ? (parseFloat(service_selling) * parseFloat(this.data.exrate)) : service_selling;

            if(service_units === "" || service_units === null){
                alert('Enter quantity');
            }

            else if(this.data.inputCur != this.data.currency){
                alert('The client Currency should be the same as input Currency. Select client ' + this.data.inputCur + 'Currency Account');
                window.location.reload();
            }
            else if (this.data.DCLink === ''){
                alert('No customer added');
            }

            else if (this.data.inputCur === ''){
                alert('Select Currency');
            }
            else if (service_selling === "" || service_selling === null){
                alert('Enter Selling prices');
            }


            else if(parseFloat(checkit) < parseFloat(getSelectedService.rate)){
                console.log(checkit);
                console.log(parseFloat(getSelectedService.rate));
                alert('Selling Price Cannot Be Below Buying Price');
            }

            else {
                 var tax = selectedTax.TaxRate/100 * service_units*service_selling;
                    addServiceToData({
                        'id':((Object.keys(this.data.services).length) + 1),
                        'service_id':getSelectedService.id,
                        'rate': (getSelectedService.rate / this.data.exrate),
                        'stock_link':getSelectedService.StockLink,
                        'selling_price':service_selling,
                        'tax_code' : selectedTax.Code,
                        'tax_description' : selectedTax.Description,
                        'tax_id' : selectedTax.idTaxRate,
                        'tax': tax,
                        'type':getSelectedService.type,
                        'unit':getSelectedService.unit,
                        'total_units':service_units,
                        'name':getSelectedService.name,
                        'total': (service_selling*service_units)+tax
                    });


                resetForm();
            };
        }

        function resetForm() {
            $('#service').val(1).trigger('change.select2');
            $('#tax').val(1).trigger('change.select2');
            $('#service_units').val('');
            $('#selling_price').val('').removeAttr('readonly');
        }

        function reloadForm() {
            window.location.reload();
        }

        function addCargo(form) {
            var formId = form.id;
            var cargo = $('#'+formId);
            var cargoDetails = cargo.serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            this.data.cargo_details = cargoDetails;

            cargo.empty();
        }

        function storeServiceData() {

            if (Object.keys(this.data.services).length > 0 && this.data.DCLink !== ''){
                this.data.type = urlParams.get('incoterm');

                axios.post('{{url('/add-services')}}', this.data)
                    .then( function (response) {
                        console.log(response);
                        window.location.href = '{{ url('/quotation') }}/' + response.data.quotation_id;
                    })
                    .catch( function (response) {
                        console.log(response.data);
                    });
            }
            else {
                var errorMsg = '';
                if (Object.keys(this.data.services).length < 1){
                    errorMsg = errorMsg + '1. No service added \n';
                }

                if (this.data.DCLink === ''){
                    errorMsg += '2. No customer added'
                }
                console.log(errorMsg);
                alert(errorMsg);
            }
        }

        function deleteSerive(service) {
            service_id = service.parentNode.parentNode;

            console.log(this.data);
            delete this.data.services[service_id.id];
            service_id.parentNode.removeChild(service_id);
            $('#total_amount').empty().append('<b>Total USD :</b>' + getTotal())

        }

        function getTotal() {
            if (Object.keys(this.data.services).length > 0){
                return Object.values(this.data.services).reduce(function (a,b) {
                    return parseFloat(a + b.total).toFixed(2);
                },0);
            }
            else{
                return 0;
            }
        }

        function addServiceToData(service) {

            $('#service_table').append('<tr id="' + service.id + '">' +
                '<td>' + service.name + '</td>' +
                '<td class="text-right">' + Number(service.total_units).toFixed(2) + '</td>' +
                '<td class="text-right">' + Number(service.selling_price).toFixed(2) + '</td>' +
                '<td class="text-right">' + Number(service.tax).toFixed(2) + '</td>' +
                '<td class="text-right">' + Number(service.total).toFixed(2)+ '</td>' +
                '<td class="text-right"><button onclick="deleteSerive(this)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></td>' +
                '</tr>');
            this.data.services[service.id] = service;
            $('#total_amount').empty().append('<b>TOTAL '+ this.data.currency + '</b> : ' + getTotal())

        }

        function fillData(dclink) {
            axios.get('{{ url('/get-customer') }}/' + dclink)
                .then( function (response) {
                    var customer = response.data.customer;

                    $('#add_customer').hide();
                    $('#client_details').empty().append(
                    '<div class="col-sm-12"><h4><botal USD : >To</b></h4>'+
                    '<h4 id="client-name"> Name : ' + customer.Name + '</h4>'+
                        '<h4 id="contact-person">Contact Person : ' + customer.Contact_Person + '</h4>'+
                        '<h4 id="contact-phone">Phone : ' + customer.Telephone + '</h4>'+
                        '<h4 id="contact-email">Email : ' + customer.EMail + '</h4></div>'
                    );
                    this.data.currency = (customer.iCurrencyID == 1 ? 'USD' : 'KES');

                    // console.log(this.data.currency, this.data.inputCur,customer );
                    $('#iccurenyc').empty().append(customer.iCurrencyID == 1 ? 'TOTAL USD' : 'TOTAL KES');
                    $('#currency').empty().append('CURRENCY ' + this.data.currency);
                })
                .catch( function (response) {
                   console.log(response.data);
                });

            this.data.DCLink = dclink;
            display_search.empty();
        }

        function currencyChangeKES() {
            this.data.inputCur = 'KES';
            $('.inputcurrency').hide();
        }
        function currencyChangeUSD() {
            this.data.inputCur = 'USD';
            $('.inputcurrency').hide();
        }
    </script>
@endsection
