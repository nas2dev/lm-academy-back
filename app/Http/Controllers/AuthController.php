<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        try {

            $validator = Validator::make($request->all(), [
                'first_name'=> 'required|string|between:2,255',
                'last_name' => 'required|string|between:2,255',
                'gender' => 'required|string',
                'email' => 'required|email|max:100|unique:users',
                'password' => 'required|string|min:6',
                'confirm_password'=> 'required|same:password',
                'image' => 'sometimes|string',
                'date_of_birth' => 'nullable',
                'academic_year' => 'nullable',
            ]);

            if($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $user = User::create(array_merge(
                $validator->validated(),
                ['password'=> bcrypt($request->password)]
            ));
            
            return response()->json([
                'message'=>'User successfully registered',
                'user'=> $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
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

        try{
            $token = JWTAuth::getToken();

            JWTAuth::invalidate($token);

            auth()->logout();

            return response()->json(['message' => 'Successfully logged out']);
        }
        catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try{
            $oldToken = JWTAuth::getToken();
            $newToken = auth()->refresh();

            if($oldToken){
                try {
                    JWTAuth::invalidate($oldToken);
                } catch(\Exception $e) {
                  \Log::warning("Token could not be invalidated");
                }
            }

            return $this->respondWithToken($newToken);
        } catch(\Exception $e) {
            return response()->json(['error' => 'Could not refresh token', "message" => $e->getMessage()], 401);
        }
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
