<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    ///LOGIN by api/////
    public function apiLogin(Request $request)
    {
        //validation part 
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password]) == false) {
                return response()->json([
                    'error' => 'The email or Password is incorrect.'
                ]);            
        }

        $client = new Client(); 

        try {
                $res = $client->request('POST', env('OAUTH_TOKEN_URL'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => $request->cid,
                    'client_secret' => $request->cp,
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope'   => "*"
                ]
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => 'OAUTH_TOKEN issue',
            ]);            
        }

        return json_decode((string) $res->getBody(), true); ///SUCCESS
    }    
}
