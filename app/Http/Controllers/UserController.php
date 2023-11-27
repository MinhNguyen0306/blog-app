<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\Follower;
use App\Models\User;
use App\Services\FCMService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function getProfileView($userId)
    {
        try {
            $user = User::findOrFail($userId);
            return view('user.profile', ['user' => $user]);
        } catch (ModelNotFoundException $ex) {
            toastr()->error("User not found");
            return redirect()->back();
        }
    }

    public function getRequestFollowers($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $requestFollowings = Follower::whereBelongsTo($user, 'toUser')
                ->where('sending_status', 'pending')
                ->orderBy('created_at', 'DESC')
                ->get();

            return view('user.request-followers', [
                'user' => $user,
                'requestFollowings' => $requestFollowings
            ]);
        } catch (ModelNotFoundException $ex) {
            toastr()->error("User not found");
            return redirect()->back();
        }
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
            ->first();

        if ($existed) {
            if ($existed->sending_status === 'pending') {
                toastr()->error("Đang chờ phản hồi");
                return redirect()->back();
            } elseif ($existed->sending_status === 'accepted') {
                $existed->sending_status = 'rejected';
                $updatedFollower = $existed->update();
                if ($updatedFollower) toastr()->success('Hủy follow thành công');
                else toastr()->error('Hủy follow thất bại');
            } else {
                $existed->sending_status = 'pending';
                $updatedFollower = $existed->update();
                if ($updatedFollower) {
                    try {
                        $this->fcmService->sendFollwingNotification($existed, "request");
                        toastr()->success('Gửi yêu cầu follow thành công');
                    } catch (Exception $ex) {
                        toastr()->error($ex->getMessage());
                    }
                } else {
                    toastr()->error('Gửi yêu cầu follow thất bại');
                }
            }
        } else {
            $follower = new Follower();
            $follower->from_user_id = $fromUserId;
            $follower->to_user_id = $toUserId;
            $follower->sending_status = 'pending';
            $savedFollower = $follower->save();
            if ($savedFollower) {
                try {
                    $this->fcmService->sendFollwingNotification($follower, "request");
                    toastr()->success('Gửi yêu cầu follow thành công');
                } catch (Exception $ex) {
                    toastr()->error($ex->getMessage());
                }
            } else {
                toastr()->error('Gửi yêu cầu follow thất bại');
            }
        }

        return redirect()->back();
    }

    public function acceptFollowing($fromUserId, $toUserId)
    {
        $existed = Follower::where('from_user_id', $fromUserId)
            ->where('to_user_id', $toUserId)
            ->first();

        if ($existed) {
            if ($existed->sending_status === 'pending') {
                $existed->sending_status = 'accepted';
                $updatedFollow = $existed->save();

                if ($updatedFollow) {
                    try {
                        $this->fcmService->sendFollwingNotification($existed, "response");
                        toastr()->success("Đã chấp nhận theo dõi");
                    } catch (Exception $ex) {
                        toastr()->error($ex->getMessage());
                    }
                } else {
                    toastr()->error("Thất bại");
                }
            } else {
                toastr()->error("Yêu cầu không hợp lệ");
            }
        } else {
            toastr()->error("Follower không tồn tại");
        }

        return redirect()->back();
    }

    public function rejectFollowing($fromUserId, $toUserId)
    {
        $existed = Follower::where('from_user_id', $fromUserId)
            ->where('to_user_id', $toUserId)
            ->first();

        if ($existed) {
            if ($existed->sending_status === 'pending') {
                $existed->sending_status = 'rejected';
                $updatedFollow = $existed->save();

                if ($updatedFollow) {
                    try {
                        $this->fcmService->sendFollwingNotification($existed, "response");
                        toastr()->success("Đã từ chối theo dõi");
                    } catch (Exception $ex) {
                        toastr()->error($ex->getMessage());
                    }
                } else {
                    toastr()->error("Thất bại");
                }
            } else {
                toastr()->error("Yêu cầu không hợp lệ");
            }
        } else {
            toastr()->error("Follower không tồn tại");
        }

        return redirect()->back();
    }

    public function cancelSendingFollowing($fromUserId, $toUserId)
    {
        $existed = Follower::where('from_user_id', $fromUserId)
            ->where('to_user_id', $toUserId)
            ->where('sending_status', '=', 'pending')
            ->update(['sending_status' => 'cancel_sending']);


        if ($existed) {
            toastr()->success('Hủy yêu cầu follow thành công');
        } else {
            toastr()->error("Bạn chưa gửi yêu cầu follow");
        }

        return redirect()->back();
    }
}
