<?php

namespace Tests\Feature\Loan;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Loan;
use Laravel\Passport\Passport;

class LoanRepaymentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    private $loanRepayment     = '/api/repayment-loan';
    private $applyLoan         = '/api/apply-loan';


    public function setUp(): void
    {
        parent::setUp();

        Passport::actingAs(factory(User::class)->create());
    }


    //test wrong test amount
    public function testloanAmount()
    {
        $loan = factory(Loan::class)->create();

        $response =  $this->json('post', $this->loanRepayment, [
            'amount'   => '40',
            'loan_number'=> $loan->id
        ]);

        $response->assertStatus(200);

         $this->assertEquals($response->decodeResponseJson()['success'], false);          
    }

    //test wrong loan-number
    public function testloanNumber()
    {
        $loan = factory(Loan::class)->create();

        $response =  $this->json('post', $this->loanRepayment, [
            'amount'   => $loan->amount,
            'loan_number'=> "999999999"
        ]);

        $response->assertStatus(200);

        $this->assertEquals($response->decodeResponseJson()['success'], false);          
    }

    //Check is apporved or not
    public function testloanApprove()
    {
        $loan = factory(Loan::class)->create(['is_approved' => 1]);
        $response =  $this->json('post', $this->loanRepayment, [
            'amount'   => number_format($loan->installment, 2),
            'loan_number'=> $loan->id
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseCount('loan_repayments', 1);  
    }

    //Check is loan reject
    public function testloanReject()
    {
        $loan = factory(Loan::class)->create(['is_approved' => 0]);

        $response =  $this->json('post', $this->loanRepayment, [
            'amount'   => number_format($loan->installment, 2),
            'loan_number'=> $loan->id
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseCount('loan_repayments', 0);
        $this->assertEquals($response->decodeResponseJson()['message'], 'Loan is not approved');         
    }


    //Check if loan is cleared or not
    public function testloanAmountCleared()
    {
        $loan = factory(Loan::class)->create(['remaining_amount' => 0, 'is_approved' => 1]);

        $response  = $this->json('post', $this->loanRepayment, [
            'amount'      => number_format($loan->installment, 2),
            'loan_number' => $loan->id
        ]);

        $response->assertStatus(200);
        $this->assertEquals($response->decodeResponseJson()['message'], 'Loan was already cleared');         
    }         
}
