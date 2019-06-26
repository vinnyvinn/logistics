<?php

namespace App\Http\Controllers;

use App\Cargo;
use App\Quotation;
use App\QuotationService;
use App\TransportService;
use Carbon\Carbon;
use Esl\helpers\Constants;
use Esl\Repository\InvNumRepo;
use Esl\Repository\StkItemRepo;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('transport.services.index')
            ->withServices(TransportService::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('transport.services.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['type'] = $request->type. ' ' .$request->subcat;
        $StockLink = StkItemRepo::init()->insertService($data);
        $data['StockLink'] = $StockLink;
        TransportService::create($data);
        return redirect('/services');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TransportService  $transportService
     * @return \Illuminate\Http\Response
     */
    public function show(TransportService $transportService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TransportService  $transportService
     * @return \Illuminate\Http\Response
     */
    public function edit($transportService)
    {
        return view('transport.services.edit')
            ->withService(TransportService::findOrFail($transportService));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TransportService  $transportService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $transportService)
    {
        TransportService::findOrFail($transportService)->update($request->all());
        return redirect('/services');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TransportService  $transportService
     * @return \Illuminate\Http\Response
     */
    public function destroy($transportService)
    {
        TransportService::findOrFail($transportService)->delete();
        return redirect('/services');
    }

    public function addService(Request $request)
    {

       // return response()->json($request->all());
          $quoteData = [
            'user_id' => Auth::user()->id,
            'DCLink' =>$request->DCLink,
            'inputCur' =>$request->inputCur,
            'type' => $request->type,
            'doc_ids' => null,
            'status' => Constants::TRANSPORT_QUOTATION_PENDING,
             'project_int' =>1
        ];

        $quotation = $request->has('quotation_id') ?
            Quotation::findOrFail($request->quotation_id) : Quotation::create($quoteData);

        if ($request->has('required_docs')){
            $dcs = json_encode($quotation->doc_ids == null ? $request->required_docs : array_merge($request->required_docs, json_decode($quotation->doc_ids,true)));

            $quotation->doc_ids = $dcs;
            $quotation->save();
        }

        if ($request->has('cargo_details')){

            $cargoDetails = $request->cargo_details;

            Cargo::create([
                'name' => $cargoDetails['cargo_name'],
                'lead_id' => Auth::user()->id,
                'quotation_id' => $quotation->id,
                'good_type_id' => 1,
                'manifest_number' => $cargoDetails['bl_no'],
                'shipping_type' => 1,
                'description' => $cargoDetails['consignee_name'],
                'type' => $cargoDetails['cargo_name'],
                'seal_no' => $cargoDetails['container_no'],
                'package' => $cargoDetails['vessel_name'],
                'weight' => $cargoDetails['cargo_qty'],
                'total_package' => $cargoDetails['cargo_qty']
            ]);

        }
 foreach ($request->services as $service) {

     QuotationService::create([
         'quotation_id' => $quotation->id,
         'service_id' => $service['service_id'],
         'name' => $service['name'],
         'rate' => $service['rate'],
         'stock_link' => $service['stock_link'],
         'selling_price' => round((float)$service['selling_price'], 2),
         'tax_code' => $service['tax_code'],
         'tax_description' => $service['tax_description'],
         'tax_id' => $service['tax_id'],
         'tax' => round((float)$service['tax'], 2),
         'type' => $service['type'],
         'unit' => round((float)$service['unit'], 2),
         'total_units' => round((float)$service['total_units'], 2),
         'total' => round((float)$service['total'], 2),
         'created_at' => Carbon::now()
     ]);

 }

        $quotation->status = Constants::LEAD_QUOTATION_PENDING;
        $quotation->save();


        return Response(['quotation_id' => $quotation->id]);
    }

    public function updateService(Request $request)
    {
        $data = $request->all();

        $service = QuotationService::findOrFail($request->service_id);

        $qt = Quotation::findOrFail($service->quotation_id);
        $qt->status = ucwords(Constants::LEAD_QUOTATION_PENDING);
        $qt->save();

        $data['tax']=($request->taxx);
        $data['selling_price']=($request->rate);
//        dd($data);
        $service->update($data);

        return Response(['success' => 'done']);
    }

    public function deleteQuotationService(Request $request)
    {
        QuotationService::findOrFail($request->service_id)->delete();
        return Response(['success' => 'success']);
    }
}
