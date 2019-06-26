<?php

namespace App;

use Esl\Repository\ESLModel;
use Illuminate\Database\Eloquent\Model;

class Incoterm extends ESLModel
{
    protected $fillable = ['name','description'];
}
