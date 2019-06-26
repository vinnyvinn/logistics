<?php

namespace App\Http\Controllers;

use App\BillOfLanding;
use App\Customer;
use App\Mail\ApprovalRequest;
use App\Mail\ProjectInvoice;
use App\Quotation;
use App\QuotationService;
use App\ServiceTax;
use App\TransportDoc;
use App\TransportService;
use Barryvdh\DomPDF\Facade as PDF;
use Esl\helpers\Constants;
use Esl\Repository\CurrencyRepo;
use Esl\Repository\CustomersRepo;
use Esl\Repository\NotificationRepo;
use Esl\Repository\QuotationRepo;
use Esl\Repository\UploadFileRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class QuotationController extends Controller
{
    public function showQuotation($id)
    {

        $quote = Quotation::with(['customer', 'services', 'cargo', 'remarks.user'])->find($id);
        if($quote->customer !=null){
            $currency = CustomersRepo::customerInit()->getCustomerCurrency($quote->customer->DCLink);
        }
        else{
            $currency='KSHS';
        }

        return view('transport.quotation.show')
            ->withQuotation($quote)
            ->withServices(TransportService::all()->sortBy('name'))
            ->withExrate(CurrencyRepo::init()->exchangeRate())
            ->withTaxs(ServiceTax::all()->sortBy('Description'))
            ->withCurrency($currency)
            ->withDocs(TransportDoc::all()->sortBy('name'));
    }

    public function requestQuotation($id)
    {
        Quotation::findOrFail($id)->update(['status' => Constants::LEAD_QUOTATION_REQUEST]);

        NotificationRepo::create()->notification(Constants::Q_APPROVAL_TITLE, 'New Approval Request',
            '/quotation/preview/' . $id, 0, 'Logistics');

        Mail::to(['email' => 'accounts@esl-eastafrica.com'])
            ->cc(Constants::EMAILS_CC)
            ->send(new ProjectInvoice(['message' => 'Kindly review this quotation ( ' . ' ' . url('/quotation/preview/' . $id) . ' ' . ' ) and advice Head of Sovereign Logistics in case the project is making loss otherwise ignore'], 'Client Quotation Review'));

        Mail::to(['email' => 'pauline.karwirwa@sovereignlog.com	'])
            ->cc(Constants::EMAILS_CC)
            ->send(new ApprovalRequest([
                'user' => Auth::user()->name,
                'url' => '/quotation/view/' . $id], 'Check Quotation'));

        alert()->success('Approval Request', 'Request sent successfully');

        return redirect()->back();
    }

    public function allQuotations()
    {
        return view('transport.quotation.all')
            ->withQuotations(Quotation::all());
    }

    public function myQuotations()
    {
        return view('transport.quotation.all')
            ->withQuotations(Quotation::where('user_id', Auth::user()->id));
    }

    public function downloadQuotation($id)
    {
        $quotation = Quotation::with(['customer', 'approvedBy', 'checkedBy', 'services', 'remarks.user'])->findOrFail($id);
        $currency = ((Customer::where('DCLink', $quotation->customer->DCLink)->get()->first()->iCurrencyID) == 1 ? 'USD' : 'KES');

        return view('pdf.invoice')
            ->withQuotation($quotation)
            ->withCurrency($currency);
        $pdf = PDF::loadView('pdf.invoice', compact(['quotation', 'currency']));
        return $pdf->download($quotation->customer->Name . '_Q00' . $quotation->id . '.pdf');
    }

    public function viewQuotation($id)
    {
        $quote = Quotation::with(['customer', 'cargo', 'services', 'remarks.user'])->findOrFail($id);

        return view('transport.quotation.view')
            ->withQuotation($quote)
            ->withCurrency(CustomersRepo::customerInit()->getCustomerCurrency($quote->customer->DCLink))
            ->withServices(TransportService::all()->sortBy('name'))
            ->withDocs(TransportDoc::all()->sortBy('name'));
    }

    public function previewQuotation($id)
    {
        $quote = Quotation::with(['customer', 'cargo', 'services'])->findOrFail($id);

        return view('transport.quotation.preview')
            ->withQuotation($quote)
            ->withCurrency(CustomersRepo::customerInit()->getCustomerCurrency($quote->customer->DCLink))
            ->withServices(TransportService::all()->sortBy('name'));
    }

    public function sendToCustomer(Request $request)
    {

        $quote = Quotation::with(['customer'])->findOrFail($request->quotation_id);
        $customer = $quote->customer;
        $customer->EMail = $request->email;
        $customer->Name = $request->name;
        $customer->Contact_Person = $request->customer_person;
        $customer->Telephone = $request->phone;

        $customer->save();

        QuotationRepo::make()->changeStatus($request->quotation_id,
            Constants::LEAD_QUOTATION_WAITING);

        Mail::to(['email' => $request->email])
            ->cc(Constants::EMAILS_CC)
            ->send(new \App\Mail\Quotation([
                'message' => $request->message,
                'user' => Auth::user()->name,
                'url' => '/quotation/preview/' . $request->quotation_id,
                'download' => '/quotation/download/' . $request->quotation_id], $request->subject));
//                'url'=>'/quotation/preview/'.$request->quotation_id],$request->subject,['address'=>$user->email]));

        alert()->success('Success', 'Quotation sent to client successfully');
        return redirect()->back();
    }

    public function pdfQuotation($id)
    {
        $quotation = Quotation::with(['lead', 'approvedBy', 'checkedBy', 'cargos.goodType', 'vessel', 'services.tariff'])->findOrFail($id);

        $pdf = PDF::loadView('quotation.pdf', compact('quotation'));
        return $pdf->download('pda.pdf');

    }

    public function customerAccept($id)
    {
        QuotationRepo::make()->changeStatus($id,
            Constants::LEAD_QUOTATION_ACCEPTED);
        $quote = Quotation::with(['customer'])->findOrFail($id);

        Mail::to(['email' => $quote->customer->EMail])
            ->cc(Constants::EMAILS_CC)
            ->send(new \App\Mail\Quotation([
                'message' => 'Quotation was successfully accepted',
                'user' => 'Sovereign Logistics',
                'url' => '/quotation/preview/' . $id,
                'download' => '/quotation/download/' . $id], 'Quotation Accepted'));

        return redirect()->back();
    }

    public function pdaStatus($status)
    {
        if ($status == Constants::LEAD_QUOTATION_PENDING) {
            $dms = Quotation::with(['lead', 'vessel', 'cargos'])->where('status',
                Constants::LEAD_QUOTATION_PENDING)->simplePaginate(25);
        }

        if ($status == Constants::LEAD_QUOTATION_REQUEST) {
            $dms = Quotation::with(['lead', 'vessel', 'cargos'])->where('status',
                Constants::LEAD_QUOTATION_REQUEST)->simplePaginate(25);
        }

        if ($status == Constants::LEAD_QUOTATION_APPROVED) {
            $dms = Quotation::with(['lead', 'vessel', 'cargos'])->where('status',
                Constants::LEAD_QUOTATION_APPROVED)->simplePaginate(25);
        }

        return view('pdas.index')
            ->withDms($dms)
            ->withStatus($status);

    }

    public function customerDecline($id)
    {
        QuotationRepo::make()->changeStatus($id,
            Constants::LEAD_QUOTATION_DECLINED_CUSTOMER);
        $quote = Quotation::with(['customer'])->findOrFail($id);

        Mail::to(['email' => $quote->customer->EMail])
            ->cc(Constants::EMAILS_CC)
            ->send(new \App\Mail\Quotation([
                'message' => 'Quotation Declined Successfully.',
                'user' => 'Sovereign Logistics',
                'url' => '/quotation/preview/' . $id,
                'download' => '/quotation/download/' . $id], 'Quotation Declined'));

        return redirect()->back();
    }

    public function convertCustomer(Request $request, $id)
    {
        QuotationRepo::make()->changeStatus($id,
            Constants::LEAD_QUOTATION_CONVERTED);

        $quotation = Quotation::with(['user', 'customer', 'services.service'])->findOrFail($id);

//        InvNumRepo::init()->makeInvoice($quotation);
        //        $leadData =  $quotation->lead;
        //        $customer = CustomersRepo::customerInit()->convertLeadToCustomer($leadData->toArray());
        $bl = BillOfLanding::create([
            'quote_id' => $quotation->id,
            'Client_id' => $quotation->DCLink,
            'cargo_id' => 0,
            'stage' => 'Pre-arrival docs',
            'status' => 0,
            'bl_number' => 0,
        ]);

        return redirect('/dms/edit/' . $bl->id);
    }

    public function serviceCost(Request $request)
    {
        $filepath = ' ';
        if ($request->has('doc_path')){
            $filepath = UploadFileRepo::init()->upload($request->doc_path);
        }

        $service = QuotationService::with(['service'])->find($request->service_id);

        if ($service->service->rate < $request->buying_price){
            alert()->error('Error','You cannot buy less than the minimum service cost');
            return redirect()->back();
        }
        $service->buying_price = $request->buying_price;
        $service->purchase_desc = $request->description;
        $service->doc_path = $filepath;
        $service->save();

        toast('Service cost add successfully');

        return redirect()->back();
    }
}
