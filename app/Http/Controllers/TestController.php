<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class TestController extends Controller
{
    public function login() {
        $auth = app('firebase.auth');
        $idTokenString = 'Z1TSFEew05NoTUFSdl7bLUN15Fm2';
        $verifiedIdToken = $auth->verifyIdToken($idTokenString);

        $uid = $verifiedIdToken->claims()->get('sub');

        $user = $auth->getUser($uid);
    }
}
