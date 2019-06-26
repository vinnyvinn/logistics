<?php

namespace App\Http\Controllers;

use App\BillOfLanding;
use App\Lead;
use App\Quotation;
use App\QuotationService;
use App\Transport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransportController extends Controller
{
    public function index()
    {
        $quotation = Quotation::with(['customer','user'])->get();
        return view('transport.dashboard.dashboard')
            ->withQuotations($quotation);
    }

    public function transport()
    {
        $quotation = Quotation::with(['customer','user'])->get();

        return view('transport.transport.index')
            ->withQuotations($quotation);
    }

    public function addTransport($id)
    {
        $dms = BillOfLanding::with(['quote.services','contracts','contracts.slubs','remarks',
            'quote.docs','customer'])->findOrFail($id);

        return view('transport.transport.add-transport')
            ->withTransport($dms);
    }

    public function storeTransport(Request $request)
    {
        $data = $request->all();

        $data['depart'] = Carbon::parse($request->depart);

        Transport::create($data);
        QuotationService::create([

                'quotation_id' => $data['quotation_id'],
                'service_id' => 1,
                'name' => $data['driver_name'],
                'rate' => $data['cost'],
                'tax_code' => 1,
                'tax_description' => 'tax out put',
                'tax_id' => 1,
                'tax' => ((16 * $data['cost']) / 100),
                'type' => 'Transport',
                'unit' => 1,
                'total_units' => 1,
                'total' => (((16 * $data['cost']) / 100) + $data['cost'])
        ]);

        return redirect('/dms/edit/'.$request->bill_of_landing_id);

    }
}
