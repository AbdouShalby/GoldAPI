<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserLoginController extends Controller
{

    public function __construct()
    {
        $this->limit = 20;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
        $method = $request->get('method');
        $response = [];
        $response['success'] = false;
        switch ($method){
            case 'email':

                $email = $request->get('email');
                $password = $request->get('password');

                if (!empty($email) && !empty($password)) {

                    $user = User::where('email', $email)->where('password', md5($password))->first();


                    if (!empty($user)) {

                            $loggedUser = $user->toArray();
                            $loggedUser['token'] = $this->adduseraccess($loggedUser['id']);
                            $response['success'] = 'Success';
                            $response['message'] = 'Login Sucessfully';
                            $response['user'] = $loggedUser;

                    } else {
                        $response['success'] = 'Failed';
                        $response['error'] = 'Incorrect Password';
                    }
                } else {
                    $response['success'] = false;
                    $response['error'] = 'some fields are missing';
                }
            break;
        }

        return response()->json($response);
    }

    public function adduseraccess($user_id)
    {
        $token = $this->getUniqAccessToken();
        $user = DB::table('user_access')->where('user_id', $user_id)->first();
        if ($user) {
            DB::table('user_access')
                ->where('id', $user->id)
                ->update(['token' => $token]);
        } else {
            DB::table('user_access')->insert(['user_id' => $user_id, 'token' => $token]);
        }
        return $token;
    }

    public function getUniqAccessToken()
    {
        $accessget = 0;
        $accessToken = '';
        while ($accessget == 0) {
            $accessToken = md5(uniqid(mt_rand(), true));
            $user = DB::table('user_access')->where('token', $accessToken)->first();
            if (!$user) {
                $accessget = 1;
            }
        }
        return $accessToken;
    }

}
