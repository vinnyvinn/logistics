<?php
/**
 * Created by PhpStorm.
 * User: vinnyvinny
 * Date: 1/17/19
 * Time: 5:22 PM
 */

namespace Esl\Repository;


use App\BillOfLanding;
use App\Lead;
use App\PurchaseOrder;

class ReportRepo
{
static function init(){
    return new self();
}
    public function getJobs($from,$to,$status){

        $jobs = '';
        if ($status=='all') {
            $jobs = BillOfLanding::whereBetween('created_at', [$from, $to])->get();
        }
        elseif ($status=='active'){
            $jobs = BillOfLanding::where('status',0)->whereBetween('created_at', [$from, $to])->get();
        }
        elseif ($status=='completed'){
            $jobs = BillOfLanding::where('status',1)->whereBetween('created_at', [$from, $to])->get();
        }

        $data = [];

        foreach ($jobs as $job){
            $data[]=[
                'Project Code' => $job->file_number,
                'Customer' => $job->customer ? ucfirst($job->customer->Name) :'',
                'Contact Person' => $job->customer ? ucfirst($job->customer->Contact_Person) :'',
                'Telephone' => $job->customer ? $job->customer->Telephone :'',
                'Type' => $job->quote->type,
                'BL/SO NO' => $job->bl_number,
                'Created' => \Carbon\Carbon::parse($job->created_at)->format('d-M-y')
            ];

        }
        return collect($data);
    }
    public function getPos($from,$to,$status)
    {

        $pos='';
        if ($status=='requested'){
            $pos = PurchaseOrder::where('status','requested')->whereBetween('created_at', [$from, $to])->get();
        }
        elseif ($status=='approved'){
            $pos = PurchaseOrder::where('status','approved')->whereBetween('created_at', [$from, $to])->get();

        }

        $data = [];

        foreach ($pos as $po){
            $data[]=[
                'Order Number' => $po->po_no,
                'Supplier' => $po->supplier ? ucfirst($po->supplier->Name) :'',
                'Created By' => ucfirst($po->user->name),
                'Status' => ucfirst($po->status),
                'PO Date' => \Carbon\Carbon::parse($po->po_date)->format('d-M-y')
            ];

        }
        return collect($data);
    }
    public function getLeads($from,$to)
    {
        $leads = Lead::whereBetween('created_at', [$from, $to])->get();
        $data = [];

        foreach ($leads as $lead){
            $data[]=[
                'Name' => ucfirst($lead->name),
                'Contact Person' => ucfirst($lead->contact_person),
                'Phone' => $lead->phone,
                'Email' => $lead->email,
                'Currency' => $lead->currency,
                'Created' => \Carbon\Carbon::parse($lead->created_at)->format('d-M-y')
            ];

        }
        return collect($data);
    }
}
