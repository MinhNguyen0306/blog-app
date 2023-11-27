<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function getPaymentPackageView()
    {
        return view('payment.payment-choose');
    }

    public function getPaymentResponseView()
    {
        return view('payment.payment-response');
    }

    public function vnpayPayment(Request $request)
    {
        // $amount = 0;
        // $payment = null;
        // if ($request->payment_package === 'silver') {
        //     $amount = 70000;
        //     $payment = Payment::create([
        //         'user_id' => Auth::user()->id,
        //         'payment_package' => 'silver',
        //     ]);
        // } else {
        //     $amount = 100000;
        //     $payment = Payment::create([
        //         'user_id' => Auth::user()->id,
        //         'payment_package' => 'gold',
        //     ]);
        // }

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://127.0.0.1:8000/payment/response";
        $vnp_TmnCode = "3BG22H4X"; //Mã website tại VNPAY
        $vnp_HashSecret = "RYELBFVYCXNLRTXDTYQMWCMJUIPMEEML"; //Chuỗi bí mật

        $vnp_TxnRef = '0303030'; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'Thanh toan hoa don thanh vien';
        $vnp_OrderType = 'Member';
        $vnp_Amount = 1000000;
        $vnp_Locale = 'VN';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
        // vui lòng tham khảo thêm tại code demo
    }

    // public function getVnpayPaymentResult()
    // {
    //     /* Payment Notify
    //     * IPN URL: Ghi nhận kết quả thanh toán từ VNPAY
    //     * Các bước thực hiện:
    //     * Kiểm tra checksum
    //     * Tìm giao dịch trong database
    //     * Kiểm tra số tiền giữa hai hệ thống
    //     * Kiểm tra tình trạng của giao dịch trước khi cập nhật
    //     * Cập nhật kết quả vào Database
    //     * Trả kết quả ghi nhận lại cho VNPAY
    //     */

    //     require_once("./config.php");
    //     $inputData = array();
    //     $returnData = array();

    //     foreach ($_GET as $key => $value) {
    //         if (substr($key, 0, 4) == "vnp_") {
    //             $inputData[$key] = $value;
    //         }
    //     }

    //     $vnp_SecureHash = $inputData['vnp_SecureHash'];
    //     unset($inputData['vnp_SecureHash']);
    //     ksort($inputData);
    //     $i = 0;
    //     $hashData = "";
    //     foreach ($inputData as $key => $value) {
    //         if ($i == 1) {
    //             $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
    //         } else {
    //             $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
    //             $i = 1;
    //         }
    //     }

    //     $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
    //     $vnpTranId = $inputData['vnp_TransactionNo']; //Mã giao dịch tại VNPAY
    //     $vnp_BankCode = $inputData['vnp_BankCode']; //Ngân hàng thanh toán
    //     $vnp_Amount = $inputData['vnp_Amount'] / 100; // Số tiền thanh toán VNPAY phản hồi

    //     $Status = 0; // Là trạng thái thanh toán của giao dịch chưa có IPN lưu tại hệ thống của merchant chiều khởi tạo URL thanh toán.
    //     $orderId = $inputData['vnp_TxnRef'];

    //     try {
    //         //Check Orderid
    //         //Kiểm tra checksum của dữ liệu
    //         if ($secureHash == $vnp_SecureHash) {
    //             //Lấy thông tin đơn hàng lưu trong Database và kiểm tra trạng thái của đơn hàng, mã đơn hàng là: $orderId
    //             //Việc kiểm tra trạng thái của đơn hàng giúp hệ thống không xử lý trùng lặp, xử lý nhiều lần một giao dịch
    //             //Giả sử: $order = mysqli_fetch_assoc($result);

    //             $order = NULL;
    //             if ($order != NULL) {
    //                 if ($order["Amount"] == $vnp_Amount) //Kiểm tra số tiền thanh toán của giao dịch: giả sử số tiền kiểm tra là đúng. //$order["Amount"] == $vnp_Amount
    //                 {
    //                     if ($order["Status"] != NULL && $order["Status"] == 0) {
    //                         if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
    //                             $Status = 1; // Trạng thái thanh toán thành công
    //                         } else {
    //                             $Status = 2; // Trạng thái thanh toán thất bại / lỗi
    //                         }
    //                         //Cài đặt Code cập nhật kết quả thanh toán, tình trạng đơn hàng vào DB
    //                         //
    //                         //
    //                         //
    //                         //Trả kết quả về cho VNPAY: Website/APP TMĐT ghi nhận yêu cầu thành công
    //                         $returnData['RspCode'] = '00';
    //                         $returnData['Message'] = 'Confirm Success';
    //                     } else {
    //                         $returnData['RspCode'] = '02';
    //                         $returnData['Message'] = 'Order already confirmed';
    //                     }
    //                 } else {
    //                     $returnData['RspCode'] = '04';
    //                     $returnData['Message'] = 'invalid amount';
    //                 }
    //             } else {
    //                 $returnData['RspCode'] = '01';
    //                 $returnData['Message'] = 'Order not found';
    //             }
    //         } else {
    //             $returnData['RspCode'] = '97';
    //             $returnData['Message'] = 'Invalid signature';
    //         }
    //     } catch (Exception $e) {
    //         $returnData['RspCode'] = '99';
    //         $returnData['Message'] = 'Unknow error';
    //     }
    //     //Trả lại VNPAY theo định dạng JSON
    //     echo json_encode($returnData);
    // }

    // public function getVnpayPaymentStatus()
    // {

    //     require_once("./config.php");
    //     $vnp_SecureHash = $_GET['vnp_SecureHash'];
    //     $inputData = array();
    //     foreach ($_GET as $key => $value) {
    //         if (substr($key, 0, 4) == "vnp_") {
    //             $inputData[$key] = $value;
    //         }
    //     }

    //     unset($inputData['vnp_SecureHash']);
    //     ksort($inputData);
    //     $i = 0;
    //     $hashData = "";
    //     foreach ($inputData as $key => $value) {
    //         if ($i == 1) {
    //             $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
    //         } else {
    //             $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
    //             $i = 1;
    //         }
    //     }

    //     $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
    //     if ($secureHash == $vnp_SecureHash) {
    //         if ($_GET['vnp_ResponseCode'] == '00') {
    //             echo "GD Thanh cong";
    //         } else {
    //             echo "GD Khong thanh cong";
    //         }
    //     } else {
    //         echo "Chu ky khong hop le";
    //     }
    // }
}
