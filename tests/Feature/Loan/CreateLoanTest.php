<?php

namespace Tests\Feature\Loan;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use DB;
use Laravel\Passport\Passport;

class CreateLoanTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    private $applyLoan = '/api/apply-loan';
    private $login     = '/api/auth/login';

    private $clientSecret;
    private $clientID = 2;

    public function setUp(): void
    {
        parent::setUp();

        $this->clientSecret = DB::table("oauth_clients")->where("id", 2)->first()->secret;
    }

    /**
     * create test user
     */
    public function createUser(){
        return factory(User::class)->create();
    }


    /**
     * Test login without params
     *
     * @return void
     */
    public function testLoginWithoutParams()
    {
        $response = $this->post($this->login);
        $response->assertStatus(200);
        
        $this->assertEquals($response->decodeResponseJson()['error'], "The email or Password is incorrect.");
    }


    /**
     * Test login without passport parameter
     *
     * @return void
     */
    public function testLoginWithoutPassportParams()
    {
        $user = $this->createUser();
        $response = $this->json('post', $this->login, [
            'email' => $user->email,
            'password' => 88888888,
        ]);        
        $response->assertStatus(200);
        $this->assertEquals($response->decodeResponseJson()['error'], "OAUTH_TOKEN issue");
    }

    /**
     * passport test
     */
    public function testOauthLogin() {

        $user = $this->createUser();

        $body = [
            'username' => $user->email,
            'password' => '88888888',
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'password',
        ];

        $this->json('POST','/oauth/token', $body)
                    ->assertStatus(200)
                    ->assertJsonStructure(['token_type','expires_in','access_token','refresh_token']);
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserLoan()
    {  
        $user = $this->createUser();

        Passport::actingAs($user);

        $response =  $this->json('post', $this->applyLoan, [
            'amount'   => '1000',
            'loan_term'  => '3'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    "success",
                    "loan-number",
                    "installment",
                    "message"
                ]);    
    }
}
