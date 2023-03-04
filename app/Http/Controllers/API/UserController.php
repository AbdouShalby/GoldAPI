<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class UserController extends Controller
{
    public function login(Request $request) {
        $method = $request->get('method');
        $response = [];
        switch ($method){
            case "email":

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

            case "firebase":
                $uid = $request->get('uid');

                try {
                    $auth = app('firebase.auth');
                    $user = $auth->getUser($uid);

                    $email = $user->email;
                    $phone = $user->phoneNumber;

                    if (!empty($email)) {
                        $user = User::where('email', $email)->first();

                        if (!empty($user)) {
                            $loggedUser = $user->toArray();
                            $loggedUser['token'] = $this->adduseraccess($loggedUser['id']);
                            $response['success'] = 'Success';
                            $response['message'] = 'Login Sucessfully';
                            $response['user'] = $loggedUser;
                        } else {
                            // Insert the user data into the database
                            $id = DB::table('users')->insertGetId(['email' => $email]);

                            // Get the user information from the inserted row
                            $user = User::where('id', $id)->first();

                            $loggedUser = $user->toArray();
                            $loggedUser['token'] = $this->adduseraccess($loggedUser['id']);
                            $response['success'] = 'Success';
                            $response['message'] = 'Login Sucessfully';
                            $response['user'] = $loggedUser;
                        }
                    }
                    else if (!empty($phone)) {
                        $user = User::where('phone', $phone)->first();

                        if (!empty($user)) {
                            $loggedUser = $user->toArray();
                            $loggedUser['token'] = $this->adduseraccess($loggedUser['id']);
                            $response['success'] = 'Success';
                            $response['message'] = 'Login Sucessfully';
                            $response['user'] = $loggedUser;
                        } else {
                            // Insert the user data into the database
                            $id = DB::table('users')->insertGetId(['phone' => $phone]);

                            // Get the user information from the inserted row
                            $user = User::where('id', $id)->first();

                            $loggedUser = $user->toArray();
                            $loggedUser['token'] = $this->adduseraccess($loggedUser['id']);
                            $response['success'] = 'Success';
                            $response['message'] = 'Login Sucessfully';
                            $response['user'] = $loggedUser;
                        }
                    } else {
                        $response['success'] = 'Failed';
                        $response['error'] = 'EMail Or Phone Not Correct';
                    }
                } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
                    $response['success'] = 'Failed';
                    $response['error'] = 'Wrong UID';
                }
                break;
        }
        return response()->json($response);
    }

    public function register() {
        $auth = app('firebase.auth');

        try {
            $user = $auth->getUser('Z1TSFEew05NoTUFSdl7bLUN15Fm2');
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            echo $e->getMessage();
        }
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
