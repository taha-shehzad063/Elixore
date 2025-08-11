<?php

namespace App\Http\Controllers\Default\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class PaymentController extends Controller
{
    public function showCheckoutForm()
    {
        return view('checkout');
    }

    public function processCheckout(Request $request)
    {
        // Validate the request
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        try {
            // Prepare transaction data
            $amountInPaisa = number_format($request->amount * 100, 0, '.', ''); // Ensure integer paisa without decimals
            $txnRefNo = 'T' . Carbon::now()->format('YmdHis') . rand(1000, 9999);
            $txnDateTime = Carbon::now()->format('YmdHis');
            $expiryDateTime = Carbon::now()->addHours(24)->format('YmdHis');

            $data = [
                "pp_Version" => "1.1",
                "pp_TxnType" => "MIGS", // Updated for card/debit card payments
                "pp_Language" => "EN",
                "pp_MerchantID" => env('JAZZCASH_MERCHANT_ID'),
                "pp_SubMerchantID" => "", // Optional, but added for consistency
                "pp_Password" => env('JAZZCASH_PASSWORD'),
                "pp_BankID" => "TBANK", // Required for sandbox testing
                "pp_ProductID" => "RETL", // Required for retail/card transactions
                "pp_TxnRefNo" => $txnRefNo,
                "pp_Amount" => $amountInPaisa,
                "pp_TxnCurrency" => "PKR",
                "pp_TxnDateTime" => $txnDateTime,
                "pp_BillReference" => "billRef",
                "pp_Description" => "Card Payment",
                "pp_TxnExpiryDateTime" => $expiryDateTime,
                "pp_ReturnURL" => env('JAZZCASH_RETURN_URL'),
                "pp_SecureHash" => "",
                // Optional ppmpf fields (remove if not needed)
                "ppmpf_1" => "1",
                "ppmpf_2" => "2",
                "ppmpf_3" => "3",
                "ppmpf_4" => "4",
                "ppmpf_5" => "5",
            ];

            // Generate hash with sorted keys (alphabetical order, as implied in docs)
            $sortedKeys = array_keys($data);
            sort($sortedKeys); // Alphabetical sort
            $hashString = env('JAZZCASH_INTEGRITY_SALT');
            foreach ($sortedKeys as $key) {
                if ($key !== "pp_SecureHash" && !empty($data[$key])) {
                    $hashString .= "&" . $data[$key];
                }
            }
            $data['pp_SecureHash'] = hash_hmac('sha256', $hashString, env('JAZZCASH_INTEGRITY_SALT'));

            // Log request data for debugging
            Log::info('JazzCash Hosted API Request: ', ['data' => $data, 'hash_string' => $hashString]);

            // Ensure endpoint is set
            $jazzcashEndpoint = env('JAZZCASH_HOSTED_ENDPOINT', 'https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform');
            if (empty($jazzcashEndpoint)) {
                Log::error('JazzCash hosted endpoint is not configured in .env');
                return back()->with('error', 'Payment gateway configuration error. Please contact support.');
            }

            // Pass data to the redirection form
            return view('jazzcash_form', [
                'data' => $data,
                'jazzcash_endpoint' => $jazzcashEndpoint,
            ]);
        } catch (\Exception $e) {
            Log::error('JazzCash Checkout Error: ' . $e->getMessage());
            return back()->with('error', 'Error initiating payment: ' . $e->getMessage());
        }
    }

    public function handleResponse(Request $request)
    {
        try {
            $responseData = $request->all();
            Log::info('JazzCash Response: ', $responseData);

            // Detect payment status (expanded based on doc response codes)
            switch ($responseData['pp_ResponseCode'] ?? 'unknown') {
                case '000':
                    return redirect()->route('checkout.form')->with('success', 'Payment successful! Transaction ID: ' . ($responseData['pp_TxnRefNo'] ?? 'N/A') . ' RRN: ' . ($responseData['pp_RetreivalReferenceNo'] ?? 'N/A'));
                case '112':
                    return redirect()->route('checkout.form')->with('warning', 'Payment is pending. Transaction ID: ' . ($responseData['pp_TxnRefNo'] ?? 'N/A'));
                default:
                    $errorMessage = $responseData['pp_ResponseMessage'] ?? 'Unknown error';
                    Log::error('JazzCash Payment Failed: ' . $errorMessage, $responseData);
                    return redirect()->route('checkout.form')->with('error', 'Payment failed: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('JazzCash Response Error: ' . $e->getMessage());
            return redirect()->route('checkout.form')->with('error', 'Error processing response: ' . $e->getMessage());
        }
    }
}