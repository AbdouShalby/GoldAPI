<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRegisterController extends Controller
{
    public function register(Request $request) {

        $name = $request->get('name');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $country = $request->get('country');
        $method = $request->get('method');
        $password = $request->get('password');
        $hashPassword = md5($password);

        $checkemail = User::where('email', $email)->first();
        $checkephone = User::where('phone', $phone)->first();

        if (!empty($method)) {
            if ($method == 'email') {
                if (empty($checkemail)) {
                    DB::insert("INSERT INTO `users`(`name`, `email`, `phone`, `country`, `balance`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `user_avatar`)
                    VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]','[value-9]','[value-10]','[value-11]','[value-12]')");
                } else {
                    $response['success'] = 'Failed';
                    $response['error'] = 'Email Already Registred Before';
                }
            }
        } else {
            $response['success'] = 'Failed';
            $response['error'] = 'You Must Send Register Method';
        }
        return response()->json($response);
    }
}
