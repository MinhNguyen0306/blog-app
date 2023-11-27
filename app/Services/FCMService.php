<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Follower;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Storage;

class FCMService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function sendNotification(Comment $createdComment, $userId)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = User::whereNotNull('device_token')->pluck('device_token')->where('id', $userId)->all();
        dd($FcmToken);

        $serverKey = 'AAAAgn6NXKk:APA91bGGv3MFksQP-ZRAZnE0ViedKQVMyxUiutmXYwVxHIF28UlsGTDQ_T7w90MGzb-Tw9SFgEYXoAWZt8ycUjdCI24pBLVpYnppnCCkc1RfR5lvE_v0ex_eJ2q4KcLXTbWEZh_V4BGY';

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $createdComment->user->name,
                "body" => $createdComment->user->name . " đã phản hồi post của bạn",
                "icon" => asset('images/Logo.svg')
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        dd($result);
    }

    public function sendFollwingNotification(Follower $follower, $typeNotification)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $data = [];
        $FcmToken = null;

        if ($typeNotification === "request") {
            $FcmToken = User::whereNotNull('device_token')
                ->pluck('device_token')
                // ->where('sending_status', 'pending')
                // ->where('to_user_id', $follower->to_user_id)
                ->all();

            $data = [
                "registration_ids" => $FcmToken,
                "notification" => [
                    "title" => "Yeu cau theo doi",
                    "body" => $follower->fromUser->name . " đã yêu cầu theo dõi",
                ]
            ];
        } else if ($typeNotification === "response") {
            $FcmToken = User::whereNotNull('device_token')
                ->pluck('device_token')
                // ->where('sending_status', $follower->sending_status)
                // ->where('from_user_id', $follower->from_user_id)
                ->all();

            $bodyMessage = $follower->sending_status === 'accepted'
                ? $follower->fromUser->name . " đã chấp nhận yêu cầu theo dõi"
                : $follower->fromUser->name . " đã từ chối yêu cầu theo dõi";

            $data = [
                "registration_ids" => $FcmToken,
                "notification" => [
                    "title" => "Yeu cau theo doi",
                    "body" => $bodyMessage,
                    "icon" => asset('images/Logo.svg')
                ]
            ];
        } else {
            throw new Exception('Message type phải là Response hoặc Request');
        }

        $serverKey = 'AAAAgn6NXKk:APA91bGGv3MFksQP-ZRAZnE0ViedKQVMyxUiutmXYwVxHIF28UlsGTDQ_T7w90MGzb-Tw9SFgEYXoAWZt8ycUjdCI24pBLVpYnppnCCkc1RfR5lvE_v0ex_eJ2q4KcLXTbWEZh_V4BGY';



        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);

        // FCM response
        // dd($result);
    }
}
