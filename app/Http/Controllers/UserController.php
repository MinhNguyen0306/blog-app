<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\Follower;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
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

    public function following($fromUserId, $toUserId)
    {
        $existed = Follower::where('from_user_id', $fromUserId)
            ->where('to_user_id', $toUserId)
            ->where('sending_status', '!=', 'pending')
            ->get();

        $follower = new Follower();
        $follower->from_user_id = $fromUserId;
        $follower->to_user_id = $toUserId;
        if (!$existed) {
            $follower->sending_status = 'pending';
        } else {
            if ($existed->sending_status === 'accepted') {
                $follower->sending_status = 'rejected';
            } else {
                $follower->sending_status = 'pending';
            }
        }

        $savedFollower = $follower->save();
        if ($savedFollower) toastr()->success('Gửi yêu cầu follow thành công');
        else toastr()->error('Gửi yêu cầu follow thất bại');

        return redirect()->back();
    }

    public function acceptFollowing($followRequestId)
    {
        try {
            $follow = Follower::findOrFail($followRequestId);

            if ($follow->sending_status === 'pending') {
                $follow->sending_status = 'accepted';
                $updatedFollow = $follow->save();

                if ($updatedFollow) toastr()->success("Accep following success");
                else toastr()->error("Accep following fail");
            } else {
                toastr()->error("Yêu cầu không hợp lệ");
            }
        } catch (ModelNotFoundException $ex) {
            toastr()->error($ex->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    public function cancelSendingFollowing($fromUserId, $toUserId)
    {
        $existed = Follower::where('from_user_id', $fromUserId)
            ->where('to_user_id', $toUserId)
            ->where('sending_status', '=', 'pending')
            ->get();

        if ($existed) {
            $existed->sending_status = 'cancel_sending';
            $savedFollower = $existed->save();

            if ($savedFollower) toastr()->success('Hủy yêu cầu follow thành công');
            else toastr()->error('Hủy yêu cầu follow thất bại');
        } else {
            toastr()->error("Ban chua gui yeu cau follow");
        }

        return redirect()->back();
    }
}
