<?php

namespace App;

use Esl\Repository\ESLModel;
use Illuminate\Database\Eloquent\Model;

class QuotationService extends ESLModel
{
    protected $fillable = ['quotation_id','stock_link','service_id','name',
        'tax_code','tax_description','tax_id','tax','selling_price',
        'unit','rate','total_units','type','total'];

    public function service()
    {
        return $this->hasOne(TransportService::class,'id','service_id');
    }
}
