<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Loan;
use App\Http\Requests\CreateLoan;
use App\Http\Requests\CreateLoanRepayment;
use App\LoanRepayment;

class LoanController extends Controller
{
    
    protected $loan;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateLoan $request)
    { 
        try {
               //create loan request
               $this->loan->fill([
                "user_id" => \Auth::user()->id,
                "amount" => $request->amount,
                "remaining_amount" => $request->amount,
                "term"  => $request->loan_term,
                "installment"=> ($request->amount/$request->loan_term) 
               ])->save();

        } catch (\Exception $ex) {

            return response()->json([
                'success' => false,
                'message' => 'Loan request was unsuccessfully',
            ]);            
        }        

        return response()->json([
                    'success' => true,
                    'loan-number' => $this->loan->id,
                    'installment' => $this->loan->installment,
                    'message' => 'Loan request created successfully'
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    //update the laon status Approve|reject
    public function loanStatus($status, $id){

        $loan = $this->loan->findOrFail($id);
        
        $loan->is_approved = $status;

        $loan->save();

        return redirect('/home');
    }


    //create repayment transaction
    public function repayment(CreateLoanRepayment $request, LoanRepayment $repayment)
    {

        $loan = $this->loan->find($request->loan_number);
        
        //if not found , throw error
        if(empty($loan)){
            return response()->json([
                'success' => false,
                'message' => 'Loan number not found',
            ]);
        }

        //check if loan approve or not
        if(!empty($loan) &&  $loan->is_approved == 0){
            return response()->json([
                'success' => false,
                'message' => 'Loan is not approved',
            ]); 
        }

        //check if all loan payment is done or not
        if(!empty($loan) &&  $loan->remaining_amount == 0){
            return response()->json([
                'success' => true,
                'message' => 'Loan was cleared',
            ]); 
        }

        //if installment amount not match , then throw error
        if($loan->installment != $request->amount){
            return response()->json([
                'success' => false,
                'message' => "Installment amount is not match, installment amount is ".$loan->installment,
            ]);

        }

        //repayment transction
        try {
               //create loan request
               $repayment->fill([
                "amount" => $request->amount,
                "loan_id"  => $request->loan_number,
               ])->save();

               //update the remaining amount
                if($loan->remaining_amount > 0) {
                    $loan->remaining_amount = $loan->remaining_amount - $request->amount;
                }

                if((float)$loan->remaining_amount < 0 )
                { 
                    $loan->remaining_amount = 0;
                }

               $loan->save();

        } catch (\Exception $ex) {
            return $ex->getMessage();
            return response()->json([
                'success' => false,
                'message' => 'Loan request was unsuccessfully',
            ]);            
        }        

        return response()->json([
                    'success' => true,
                    'remaining-loan' => number_format($loan->remaining_amount, 2),
                    'message' => 'Loan repayment was successfully'
                ]);

    }//end repayment
}
