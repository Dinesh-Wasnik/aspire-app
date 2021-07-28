<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    protected $fillable = [
        "loan_id"
        ,"amount"
    ];
}
