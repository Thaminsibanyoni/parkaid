<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayfastService
{
    protected $merchantId;
    protected $merchantKey;
    protected $passphrase;
    protected $testMode;
    protected $baseUrl;

    public function __construct()
    {
        $this->merchantId = config('services.payfast.merchant_id');
        $this->merchantKey = config('services.payfast.merchant_key');
        $this->passphrase = config('services.payfast.passphrase');
        $this->testMode = config('services.payfast.test_mode', true);
        $this->baseUrl = $this->testMode ? 'https://sandbox.payfast.co.za' : 'https://www.payfast.co.za';
    }

    public function generatePaymentUrl(Booking $booking, Payment $payment)
    {
        $returnUrl = route('payment.success') . '?booking_id=' . $booking->id;
        $cancelUrl = route('payment.cancel') . '?booking_id=' . $booking->id;
        $notifyUrl = route('payment.webhook');

        $data = [
            // Merchant details
            'merchant_id' => $this->merchantId,
            'merchant_key' => $this->merchantKey,
            'return_url' => $returnUrl,
            'cancel_url' => $cancelUrl,
            'notify_url' => $notifyUrl,

            // Transaction details
            'amount' => number_format($payment->amount, 2, '.', ''),
            'm_payment_id' => $booking->id,
            'item_name' => 'Booking #' . $booking->id,
            'item_description' => 'Parking at ' . $booking->parkingSpace->name,

            // Customer details
            'name_first' => $booking->user->name,
            'email_address' => $booking->user->email,
        ];

        // Generate signature
        $signature = $this->generateSignature($data);
        $data['signature'] = $signature;

        // If test mode, add testing flag
        if ($this->testMode) {
            $data['test_mode'] = 1;
        }

        // Build the query string
        $queryString = http_build_query($data);

        // Return the full payment URL
        return "{$this->baseUrl}/eng/process?{$queryString}";
    }

    public function validateCallback(Request $request)
    {
        // Verify source IP
        $validHosts = [
            'www.payfast.co.za',
            'sandbox.payfast.co.za',
            'w1w.payfast.co.za',
            'w2w.payfast.co.za',
        ];

        $validIps = [];
        foreach ($validHosts as $host) {
            $ips = gethostbynamel($host);
            if ($ips !== false) {
                $validIps = array_merge($validIps, $ips);
            }
        }

        $validIps = array_unique($validIps);

        if (!in_array($request->ip(), $validIps) && !$this->testMode) {
            Log::error('PayFast webhook called from invalid IP: ' . $request->ip());
            return false;
        }

        // Convert request data to array
        $pfData = $request->all();

        // Remove signature from data to validate
        $pfParamString = '';
        foreach ($pfData as $key => $val) {
            if ($key !== 'signature') {
                $pfParamString .= $key . '=' . urlencode($val) . '&';
            }
        }

        // Remove last & from string
        $pfParamString = rtrim($pfParamString, '&');

        // Generate signature
        $signature = md5($pfParamString);

        return $signature === $request->get('signature');
    }

    protected function generateSignature($data)
    {
        // Create parameter string
        $pfOutput = '';
        foreach ($data as $key => $val) {
            $pfOutput .= $key . '=' . urlencode($val) . '&';
        }

        // Remove last & from string
        $pfOutput = rtrim($pfOutput, '&');

        // Add passphrase if not empty
        if (!empty($this->passphrase)) {
            $pfOutput .= '&passphrase=' . urlencode($this->passphrase);
        }

        // Generate signature
        return md5($pfOutput);
    }
}
