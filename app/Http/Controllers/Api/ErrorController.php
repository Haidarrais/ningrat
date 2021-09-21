<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ErrorController extends Controller
{
    public function check_home(Request $request) {
        $api_token = $request->api_token;
        $user = User::findToken($api_token);
        if(!$user->member) {
            return response()->json([
                'status' => false,
                'message' => [
                    'head' => 'Gagal',
                    'body' => "Silahkan lengkapi profil terlebih dahulu"
                ]
            ], 500);
        }
    }
}
