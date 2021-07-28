<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Loan;
use App\Http\Requests\CreateLoan;
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
}
