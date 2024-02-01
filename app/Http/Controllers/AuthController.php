<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(LoginRequest $request)
    {
    	extract($request->all());
    	return User::create([
    		'email' => $email,
    		'nickname' => $nickname,
    		'role' => isset($role) ? $role : 'learner',
    		'password' => Hash::make($password),
    	]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
    	extract($request->all());
    	try {
    		//User can login/sign-up using their credentials
    		$user=User::where('email',trim($email))->first();
            if($user && Hash::check($password,$user->password)){
                $user->id = $user->_id;
                return response(['user' => $user,'token'=>auth()->login($user),'status' => true]);
    		}else{
                // TO DO if user has email request for forgot password 

                //If the user is not registered on the database, they are automatically registered using the entered credentials. They are marked as learners.
    			$status = $this->create($request);
    			if(!empty($status) && $status != null){
    				$login = $this->login($request);
    				return $login;
    			}   		
    			else{
    				return response()->json(['status' => 0, 'message' => 'Something went wrong!']);
    			}
    		}
    	} catch (Exception $e) {
    		return response()->json(['status' => 0, 'message' => 'Error while proccesing']);
    	}
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
    	return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
    	auth()->logout();

    	return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
    	return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
    	return response()->json([
    		'access_token' => $token,
    		'token_type' => 'bearer',
    		'expires_in' => auth()->factory()->getTTL() * 60
    	]);
    }
}