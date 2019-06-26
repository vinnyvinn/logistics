<?php

namespace App;

use Esl\Repository\ESLModel;
use Illuminate\Database\Eloquent\Model;

class BillOfLanding extends ESLModel
{
    protected  $fillable = ['code_name','file_number','contract_ids','client_notification','quote_id','start','destination',
        'Client_id','cargo_weight','desc','remarks','ctm_ref','shipper','shipping_line','seal_number','status','distance','bl_number','stage'];


    public function quote()
    {
        return $this->hasOne(Quotation::class, 'id', 'quote_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'DCLink','Client_id');
    }

    public function contracts()
    {
        return $this->hasOne(Contract::class,'id','contract_ids');
    }

    public function remarks()
    {
        return $this->hasMany(CtmRemark::class,'ctm_id', 'id');
    }

    public function transports()
    {
        return $this->hasMany(Transport::class,'bill_of_landing_id','id');
    }

    public function images()
    {
        return $this->hasMany(CargoImage::class,'bill_of_landing_id','id');
    }

    public function deliverynotes()
    {
        return $this->hasMany(DeliveryNote::class,'bol_id', 'id');
    }
}
