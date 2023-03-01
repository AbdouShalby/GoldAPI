<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class TestController extends Controller
{
    public function login() {
        $auth = app('firebase.auth');

        try {
            $user = $auth->getUser('Z1TSFEew05NoTUFSdl7bLUN15Fm2');
        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            echo $e->getMessage();
        }
    }
}
