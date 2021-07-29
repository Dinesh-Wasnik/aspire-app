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
    use WithFaker;
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
     * Test login with all parameter
     *
     * @return void
     */
    public function testLogin()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->json('post', $this->login, [
          'email' => $user->email,
          'password' => 88888888,
            'cp' => $this->clientSecret,
            'cid' => $this->clientID
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token'
        ]);               
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserLoan()
    {  
        // $user = $this->createUser();

        // $cp = DB::table("oauth_clients")->where("id", 2)->first()->secret;

        // $response = $this->json('post', $this->login, [
        //     'cp' => $cp,
        //     'cid' => 2,
        //     'email' => $user->email,
        //     'password' => '88888888',
        // ]);

        // $response->assertStatus(422)->assertJson([
        //     'error' => [
        //         'message' => 'Amount not valid'
        //     ]
        // ]);

        // $response = $this->post($this->applyLoan);

        // $response->assertStatus(201);
    }
}
