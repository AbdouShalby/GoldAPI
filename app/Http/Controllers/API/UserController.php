<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class UserController extends Controller
{
    public function login() {
        $auth = app('firebase.auth');

        try {
            $user = $auth->getUser('Z1TSFEew05NoTUFSdl7bLUN15Fm2');
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            echo $e->getMessage();
        }

        return response()->json($user);
    }

    public function register() {
        $auth = app('firebase.auth');

        try {
            $user = $auth->getUser('Z1TSFEew05NoTUFSdl7bLUN15Fm2');
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            echo $e->getMessage();
        }
    }
}
