<?php
/**
 * Created by PhpStorm.
 * User: marvin
 * Date: 5/16/18
 * Time: 5:16 PM
 */

namespace Esl\Repository;


use App\Mail\ProjectInvoice;
use App\Project;
use Carbon\Carbon;
use Esl\helpers\Constants;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ProjectRepo
{
    protected $projectName;
    public static function init()
    {
        return new self;
    }

//    public function generateName($name, $imo)
//    {
//        $name = preg_replace("/[^a-zA-Z0-9]+/", "", $name);
//        $pname = '';
//        if (count(Vessel::where('imo_number',$imo)->get()) > 1){
//            foreach (explode(" ",$name) as $key => $value){
//                if ($key == 1){
//                    $pname = $pname. substr($value,4,3);
//                }
//                else{
//                    $pname = $pname. mb_strimwidth($value,0,3);
//                }
//            }
//        }
//        else{
//            foreach (explode(" ",$name) as $value){
//                $pname = $pname. mb_strimwidth($value,0,3);
//            }
//        }
//
//        $this->projectName = $this->checkIfProjectExist($pname);
//        return $this;
//    }

    public function getName()
    {
        return $this->projectName;
    }

//    private function checkIfProjectExist($name)
//    {
//        $numberOftime = count(Project::where('ProjectName',$name)->get());
//
//        $currentYear = Carbon::now()->format('y');
//        $currentMonth = Carbon::now()->format('m');
//
//        return strtoupper($name.'-0'.$numberOftime.'/'.$currentYear);
//    }

    public function getProjectNumber($id)
    {
        return Project::where('ProjectLink',$id)->get()->first()->ProjectName;
    }

    public function makeProject($projectNumber)
    {
        Mail::to(['email'=>'accounts@esl-eastafrica.com'])
            ->cc(Constants::EMAILS_CC)
            ->send(new ProjectInvoice(['message'=>'Project '.$projectNumber.
                ' has been created by '. ucwords(Auth::user()->name) . ' on '.Carbon::now()->format('d-M-y H:m'). '. Kindly prepare in advance '],'PROJECT '. $this->projectName . ' CREATED'));

        return Project::insertGetId([
                'ProjectName' => $projectNumber,
                'ProjectCode' => $projectNumber,
                'ActiveProject' => 1,
                'MasterSubProject' => $projectNumber,
                'ProjectDescription' => $projectNumber,
                'ProjectLevel' => 0,
//            'Project_iChangeSetID' =>,
//            'Project_iCreatedAgentID',
//            'Project_iCreatedBranchID',
//            'Project_iModifiedAgentID',
//            'Project_iModifiedBranchID',
                'SubProjectOfLink' => 0,
//            'Project_Checksum' => ,
                'Project_dCreatedDate' => Carbon::now(),
//            'Project_dModifiedDate' => null,
                'Project_iBranchID' => 0
            ]
        );
    }
}