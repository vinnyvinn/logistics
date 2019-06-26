<?php

namespace App\Http\Controllers;

use App\BillOfLanding;
use App\DeliveryNote;
use App\DmsComponent;
use App\Mail\ProjectInvoice;
use App\Project;
use App\Quotation;
use App\Sof;
use App\Stage;
use App\VesselDocs;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Esl\helpers\Constants;
use Esl\Repository\InvNumRepo;
use Esl\Repository\ProjectRepo;
use Esl\Repository\UploadFileRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DmsController extends Controller
{
    public function index()
    {
        $bl = BillOfLanding::with(['quote.services','customer'])->get();

        return view('dms.index')
            ->withDms($bl);
    }

    public function edit($id)
    {
        //updating job details
        $dms = BillOfLanding::with(['quote.services','quote.pettyCash.user','deliverynotes','transports','images',
            'contracts','quote.cargo','remarks','quote.docs','customer'])->findOrFail($id);
        $dmsComponents = DmsComponent::with(['scomponent.stage'])
            ->where('bill_of_landing_id',$id)->get();
        $checklist = $dmsComponents->map(function ($value){
            return [
                'title' => $value->scomponent->stage->name,
                $value->scomponent->stage->name => [
                    'name' => $value->scomponent->name,
                    'type' => $value->scomponent->type,
                    'notification' => $value->scomponent->notification,
                    'timing' => $value->scomponent->timing,
                    'doc_links' => json_decode($value->doc_links),
                    'text' => $value->text,
                    'subchecklist' => $value->subchecklist,
                    'created_at' => $value->created_at
                ]
            ];
        })->reject(null);

        $update = false;
        if ($dms->start == null || $dms->destination == null || $dms->distance == null){
            $update = true;
        }

        return view('dms.edit')
            ->withDms($dms)
            ->withChecklist($checklist->groupBy('title'))
            ->withUpdate($update)
            ->withStages(Stage::with(['components'])->get());
    }

    public function reportTransportRevenue()
    {
        $dms = BillOfLanding::with(['transports',
            'contracts','remarks','quote.docs','customer'])->get();

        return view('reports.transport-revenue')
            ->withDmss($dms);

    }

    public function downloadReport(Request $request)
    {
        $data = BillOfLanding::with(['transports',
            'contracts','remarks','quote.docs','customer'])
            ->whereBetween('created_at',[Carbon::parse($request->date1), Carbon::parse($request->date2)])->get();

        return view('reports.download-transport-revenue')
            ->withDmss($data);
    }

    public function store(Request $request)
    {
        $data = [];

        if ($request->has('checklist')){

            foreach ($request->checklist as $key => $check){
                $checklist = [];
                foreach ($check as $inner_key => $item){
                    array_push($checklist,$inner_key);
                }
                array_push($data,[$key => ['components'=>json_encode($checklist)]]);
            }

        }

        if ($request->has('text_value')){

            foreach ($request->text_value as $key => $item){
                array_push($data,[$key => ['text' => $item[0]]]);
            }
        }

        if ($request->has('remark')){

            foreach ($request->remark as $key => $item){
                array_push($data,[$key => ['remark' => $item[0]]]);
            }
        }

        if ($request->has('doc_links')){
            foreach ($request->doc_links as $key => $doc_link){
                $doc_array = [];
                foreach ($doc_link as $doc){
                    $image = $doc;
                    $name = time().'.'.$image->getClientOriginalExtension();
                    $filepath = 'documents/uploads/';

                    $image->move(public_path('documents/uploads/'),$name);
                    array_push($doc_array,$filepath.$name);
                }

                array_push($data,[$key => ['doc_links' => json_encode($doc_array)]]);

            }
        }

        $keys = [];

        $insertData = [];
        $now = Carbon::now();
        foreach ($data as $key => $datum){
            foreach ($datum as $data_key => $value){
                foreach ($value as $xkey => $inner){
                    if (!array_key_exists($data_key,$keys)){
                        array_push($insertData,[
                            'bill_of_landing_id' => $request->dms_id,
                            'stage_component_id' => $data_key,
                            'doc_links' => $xkey == "doc_links" ? $inner : null,
                            'text' => $xkey == "text" ? $inner : null,
                            'remark' => $xkey == "remark" ? $inner : null,
                            'subchecklist' => $xkey == "components" ? $inner : null,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                        $keys[$data_key] = $data_key;
                    }
                    else{
                        foreach ($insertData as $skey => $test){
                            array_push($insertData,[
                                'bill_of_landing_id' => $request->dms_id,
                                'stage_component_id' => $data_key,
                                'doc_links' => ($xkey == "doc_links" && $test['doc_links'] == null) ? $inner : $test['doc_links'],
                                'text' => ($xkey == "text"  && $test['text'] == null) ? $inner : $test['text'],
                                'remark' => ($xkey == "remark"  && $test['remark'] == null) ? $inner : $test['remark'],
                                'subchecklist' => ($test['subchecklist'] == null && $xkey == "components") ? $inner : $test['subchecklist'],
                                'created_at' => $now,
                                'updated_at' => $now
                            ]);

                            unset($insertData[$skey]);
                            break;
                        }
                    }

                }
            }
        }

        DmsComponent::insert($insertData);
        return redirect()->back();
    }

    public function addSof(Request $request)
    {
        $data = $request->all();
        $data['from'] = Carbon::parse($request->from);
        $data['to'] = Carbon::parse($request->to);


        $sof = Sof::create($data);

        return Response(['success' => '<tr>'.
            '<td>'.$sof->created_at.'</td>'.
            '<td>'. Carbon::parse($sof->from)->format('H:i') .'HRS</td>'.
            '<td>'. Carbon::parse($sof->to)->format('H:i') .'HRS</td>'.
            '<td>'. $sof->crane_working.'</td>'.
            '<td>'. ucfirst($sof->remarks).'</td>'.
            '</tr>']);
    }

    public function updateDms(Request $request)
    {
        $data = $request->all();
        $file_no = 'S'.$request->job_type.'/'.Carbon::now()->format('y').'/'.strtoupper($request->seal_number).'/0'.(count(Project::all())+1);
        $data['seal_number']= $file_no;
        $data['file_no']= $file_no;
        $data['ctm_ref']= $request->ctm_ref == null ? $file_no : $request->ctm_ref;

        $dms = BillOfLanding::with(['quote'])->findOrFail($request->dms_id);
        $dms->update($data);

        $quote = Quotation::findOrFail($dms->quote_id);
        $quote->project_int =  ProjectRepo::init()->makeProject($file_no);
        $quote->save();

        alert()->success('Update', 'Project updated successfully and email sent to finance');

        return redirect()->back();
    }

    public function deliveryNote(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $data['doc_path'] = UploadFileRepo::init()->upload($request->doc,'documents');
        DeliveryNote::create($data);

        return redirect()->back();
    }

    public function generateLayTime($id)
    {
        $dms = BillOfLanding::with(['sof','cargo','images','vessel','customer','quote.voyage'])->findOrFail($id);

        $port_stay = ceil($dms->vessel->grt/$dms->discharge_rate);

        $laytime = [];
        $lowerpart['timeallowed'] = $this->getTimeDeatils(($port_stay * 24 * 60 * 60));
        ;

        foreach ($dms->sof->sortByDesc('created_at') as $sof){
            array_push($laytime,[
                'day' => Carbon::parse($sof->created_at)->format('l'),
                'date' => Carbon::parse($sof->created_at)->format('d-M-y'),
                'period' => Carbon::parse($sof->from)->format('H:i').' HRS - '.Carbon::parse($sof->to)->format('H:i').' HRS',
                'time_to_count' =>  ($sof->crane_working * 100) / $dms->number_of_crane,
                'days' =>  $this->getTimeDeatils(strtotime(Carbon::parse($sof->to))-strtotime(Carbon::parse($sof->from))),
                'remarks' => $sof->remarks,
                'secs' => abs(strtotime(Carbon::parse($sof->to))-strtotime(Carbon::parse($sof->from)))
            ]);
        }

        $lowerpart['laytimeused'] = $this->getTimeDeatils(collect($laytime)->sum('secs'));
        $lowerpart['timesave'] = $this->getTimeDeatils(($port_stay * 24 * 60 * 60) - collect($laytime)->sum('secs'));
        $data = [
            $lowerpart,
            $laytime,
            [
                'vesselname' => $dms->vessel->name,
                'bl' => $dms->bl_number,
                'supplier' => $dms->cargo->first()->shipper,
                'arrive' => Carbon::parse($dms->quote->voyage->vessel_arrived)->format('d-M-y'),
                'weight' => $dms->vessel->grt,
                'rate' => $dms->discharge_rate,
                'time' =>$dms->time_allowed,
                'ltime' =>$dms->laytime_start,
            ]

        ];


        $pdf = PDF::loadView('pdf.laytime',compact('data'));
        return $pdf->download('laytime.pdf');

        return view('pdf.laytime')
            ->withData($data);
    }

    public function getTimeDeatils($sec)
    {
        $dt1 = new DateTime("@0");
        $dt2 = new DateTime("@$sec");
        return $dt1->diff($dt2)->format('%a, %h, %i');
    }

    public function complete($id)
    {

        $ctm = BillOfLanding::findOrFail($id);
        $ctm->update(['status'=>1]);

        $quotation = Quotation::with(['user','customer','services.service'])->findOrFail($ctm->quote_id);
        $quotation->status = Constants::LEAD_QUOTATION_COMPLETED;


        $message = 'Update the cost of ';
        $costUpdate = true;

        foreach ($quotation->services as $service){
            if ($service->buying_price == null && $service->buying_price == 0){
                $message = $message.' '.$service->service->name." \n";
                $costUpdate = false;
            }
        }

        if (!$costUpdate){
            alert()->error('Error',$message);

            return redirect()->back();
        }



        $quotation->save();

        $projectName = ProjectRepo::init()->getProjectNumber($quotation->project_int);


        Mail::to(['email'=>'accounts@esl-eastafrica.com'])
            ->cc(Constants::EMAILS_CC)
            ->send(new ProjectInvoice(['message'=>'Project '.$projectName.
                ' has been successfully closed by '. ucwords(Auth::user()->name) .
                ' on '.Carbon::now()->format('d-M-y H:m'). '. Kindly generate the Tax invoice for the project, thank you for your support'],
                'PROJECT '. $projectName . ' COMPLETED'));

        InvNumRepo::init()->makeInvoice($quotation);

        alert()->success('Completed','Project completed successfully invoice sent to Sage');

        return redirect('/dsr');

    }

}
