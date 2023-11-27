<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotificationPage()
    {
        return view('notification.index');
    }

    public function storeDeviceToken(Request $request)
    {
        $currentUser = User::find(Auth::user()->id);

        if ($currentUser->device_token) {
            $currentUser->device_token = null;
            $updatedUser = $currentUser->save();
            if ($updatedUser) return response()->json(['offSuccess' => 'Device token has been deleted']);
            return response()->json(['offFail' => 'Token failed deleted']);
        } else {
            $currentUser->device_token = $request->token;
            $updatedUser = $currentUser->save();
            if ($updatedUser) return response()->json(['success' => 'Token successfully stored.']);
            return response()->json(['fail' => 'Token failed stored']);
        }
    }
}
