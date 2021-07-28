<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class CreateLoanRepayment extends FormRequest
{   

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user() != null && 
            empty(Auth::user()->toArray())){
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount'=>'required|regex:/^\d+(\.\d{1,2})?$/',
            'loan_number'=>'required|numeric'
        ];
    }
}
