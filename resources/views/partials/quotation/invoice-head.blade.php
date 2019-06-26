<div class="col-md-12">
    <div class="pull-left">
        <address>
            <img src="{{ asset('images/logo.png') }}" alt="">
            <p style="font-size: smaller">Powering Our Customers to be Leaders in their Markets</p>
            <h4>CANNON TOWERS 6TH FLOOR, MOI AVENUE <br>
                MOMBASA</h4>
        </address>
    </div>
    <div class="pull-right">
        <address id="client_details">
            <h3>{{ $quotation->status != \Esl\helpers\Constants::LEAD_QUOTATION_ACCEPTED ? 'Quotation' : 'Proforma Invoice'}}</h3>
            <h4>Tax Registration: P051372811P</h4>
            <h4>Telephone: +254 41 2229784</h4>
        </address>
    </div>
</div>
<div class="col-sm-12">
    <hr>
</div>
<div class="col-md-12">
    <div class="pull-left">
        <address>
            <h4><b>To</b></h4>
            <h4>Name : {{ @ucwords($quotation->customer->Name) }} </h4>
            <h4>Contact Person : {{ @mb_strimwidth(ucwords($quotation->customer->Contact_Person),0,16,"...") }} </h4>
            <h4>Phone : {{ @$quotation->customer->Telephone }} </h4>
            <h4>Email :  {{ @$quotation->customer->EMail }}</h4>
            <br>
            <p><b>Date : </b> {{ \Carbon\Carbon::parse($quotation->created_at)->format('d-M-y') }}</p>
        </address>
    </div>
    <div class="pull-right">
        <address id="client_details">
            <h4><b>B/L NO: </b>{{ @strtoupper($quotation->cargo->manifest_number )}}</h4>
            <h4><b>CARGO: </b>{{ @strtoupper($quotation->cargo->name )}}</h4>
            <h4><b>VESSEL: </b>{{ @strtoupper($quotation->cargo->package )}}</h4>
            <h4><b>QUANTITY: </b>{{ @strtoupper($quotation->cargo->weight )}}</h4>
            <h4><b>C'NER: </b>{{ @strtoupper($quotation->cargo->seal_no )}}</h4>
            <h4><b>CONSIGNEE: </b>{{ @strtoupper($quotation->cargo->description )}}</h4>
        </address>
    </div>
</div>
<div class="col-sm-12">
    <hr>
</div>
