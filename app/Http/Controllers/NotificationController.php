<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function updateDeviceToken(Request $request)
    {
        $currentUser = User::find(Auth::user()->id);
        $currentUser->device_token =  $request->token;

        $updatedUser = $currentUser->save();

        if ($updatedUser) return response()->json(['success' => 'Token successfully stored.']);

        return response()->json(['fail' => 'Token failed stored.']);
    }
}
