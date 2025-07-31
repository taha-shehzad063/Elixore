<?php

namespace App\Http\Controllers\Default\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Show the AlfaPay payment form.
     */
    public function showForm()
    {
        return view('front.default.payments.alfah');
    }

    /**
     * Process the payment by redirecting to Bank Alfalah with encrypted hash.
     */
    public function payWithAlfa(Request $request)
    {
        $orderId = 'ORD-' . uniqid();
        $amount = $request->input('amount', '100');

        // Use HTTPS return URL explicitly to avoid protocol mismatch
        $returnUrl = secure_url('payment/return');

        $payload = [
            "HS_ChannelId" => env('ALFAPAY_CHANNEL_ID'), // "1001"
            "HS_MerchantId" => env('ALFAPAY_MERCHANT_ID'), // "31930"
            "HS_StoreId" => env('ALFAPAY_STORE_ID'), // "219600"
            "HS_ReturnURL" => $returnUrl, // https://...
            "HS_MerchantHash" => env('ALFAPAY_MERCHANT_HASH'),
            "HS_MerchantUsername" => env('ALFAPAY_MERCHANT_USERNAME'),
            "HS_MerchantPassword" => env('ALFAPAY_MERCHANT_PASSWORD'),
            "HS_TransactionReferenceNumber" => $orderId,
            "HS_TransactionAmount" => $amount,
            "HS_TransactionTypeId" => "3",
            "HS_IsRedirectionRequest" => "1",
        ];

        $aesKey = env('ALFAPAY_KEY_1');
        $aesIV = env('ALFAPAY_KEY_2');

        // Construct map string exactly as "key=value" joined by &
        $mapString = implode('&', array_map(
            fn($k, $v) => "$k=$v",
            array_keys($payload),
            $payload
        ));

        // Encrypt and base64 encode
        $requestHash = base64_encode(openssl_encrypt($mapString, 'AES-128-CBC', $aesKey, OPENSSL_RAW_DATA, $aesIV));
        $payload['HS_RequestHash'] = $requestHash;

        // Build map string excluding HS_RequestHash for logging
        $mapStringForLog = collect($payload)
            ->filter(fn($val, $key) => !empty($val) && $key !== "HS_RequestHash")
            ->map(fn($val, $key) => "$key=$val")
            ->implode("&");

        Log::info('AlfaPay MapString', ['mapString' => $mapStringForLog]);
        Log::info('Sending AlfaPay request', $payload);

        // Use correct API endpoint URL from docs for redirection
        $response = Http::asForm()->post(env('ALFAPAY_API_URL'), $payload);
        $responseBody = $response->body();

        Log::info('AlfaPay Raw Response', ['body' => $responseBody]);

        $responseData = json_decode($responseBody, true);
        if (is_string($responseData)) {
            $responseData = json_decode($responseData, true);
        }

        Log::info('AlfaPay Parsed Response', is_array($responseData) ? $responseData : ['response' => $responseData]);

        if (isset($responseData['success']) && $responseData['success'] === "true" && !empty($responseData['ReturnURL'])) {
            Log::info('AlfaPay Payment Redirecting', ['url' => $responseData['ReturnURL']]);
            return redirect()->away($responseData['ReturnURL']);
        }

        $error = $responseData['ErrorMessage'] ?? 'Payment initiation failed.';
        Log::error('AlfaPay Payment Failed', ['error' => $error]);
        return back()->with('error', $error);
    }

    private function encryptRequest($string)
    {
        $key1 = env('ALFAPAY_KEY_1'); // Must be 16 bytes
        $key2 = env('ALFAPAY_KEY_2'); // Must be 16 bytes

        $cipher = "AES-128-CBC";

        $encrypted = openssl_encrypt($string, $cipher, $key1, OPENSSL_RAW_DATA, $key2);

        return base64_encode($encrypted);
    }

    /**
     * Handle the return response from AlfaPay after redirection.
     */
    public function handleReturn(Request $request)
    {
        Log::info('AlfaPay Return Hit', ['request' => $request->all()]);
        // You can verify response data, update order status, etc.
        return view('front.default.payments.return'); // or redirect to a status page
    }

    /**
     * Handle IPN notifications (if Bank Alfalah sends them).
     */
    public function handleIPN(Request $request)
    {
        Log::info('📡 AlfaPay IPN Notification:', ['request' => $request->all()]);
        dd('ok');
        // Optional: You can verify transaction ID and update DB

        return response('OK', 200);
    }
}
