<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function getProfileView($userId)
    {
        $user = User::findOrFail($userId);
        // dd($user);
        return view('user.profile', ['user' => $user]);
    }

    public function getEditView()
    {
        return view('user.edit');
    }

    public function update(Request $request, $userId)
    {
        $request->validate([
            'avatar-upload' => 'dimensions:max_width=1000,max_height=800'
        ]);

        try {
            $user = User::findOrFail($userId);
            $newFile = '';
            if ($request->has('avatar-upload')) {
                $file = $request->file('avatar-upload');
                $fileName = $file->hashName();
                $newFile = time() . '-' . $fileName;
                $file->move(public_path('/images'), $newFile);
            }

            $request->merge(['avatar' => $newFile]);
            $userUpdated = $user->update($request->all());

            if ($userUpdated) {
                toastr()->success('Edit profile sucessfully');
            } else {
                toastr()->error('Edit profile failed!');
            }
        } catch (ModelNotFoundException $ex) {
            toastr()->error($ex->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
