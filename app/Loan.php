<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        "user_id"
        , "amount"
        , "term"
        , "installment"
        , "is_approved"
    ];


    /**
     * Get the user associated with loan.
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }    
}
