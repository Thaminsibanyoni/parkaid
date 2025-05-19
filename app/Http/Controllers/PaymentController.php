<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::all();

        return response()->json($payments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'transaction_id' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'payment_method' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,completed,failed,refunded',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $payment = Payment::create([
            'booking_id' => $request->booking_id,
            'transaction_id' => $request->transaction_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => $request->status,
        ]);

        return response()->json($payment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::findOrFail($id);

        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'exists:bookings,id',
            'transaction_id' => 'string|max:255',
            'amount' => 'numeric',
            'payment_method' => 'string|max:255',
            'status' => 'in:pending,completed,failed,refunded',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $payment = Payment::findOrFail($id);

        $payment->booking_id = $request->input('booking_id', $payment->booking_id);
        $payment->transaction_id = $request->input('transaction_id', $payment->transaction_id);
        $payment->amount = $request->input('amount', $payment->amount);
        $payment->payment_method = $request->input('payment_method', $payment->payment_method);
        $payment->status = $request->input('status', $payment->status);

        $payment->save();

        return response()->json($payment, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);

        $payment->delete();

        return response()->json(null, 204);
    }
}
