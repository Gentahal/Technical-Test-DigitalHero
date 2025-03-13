<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class BookingController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('booking.index', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'booking_date'  => 'required|date',
            'service_id'    => 'required|exists:services,id',
        ]);

        $service = Service::find($request->service_id);

        $totalPrice = $service->price;

        if (in_array(date('N', strtotime($request->booking_date)), [6, 7])) {
            $totalPrice += 50000;
        }

        $booking = Booking::create([
            'customer_name'  => $request->customer_name,
            'booking_date'   => $request->booking_date,
            'service_id'     => $request->service_id,
            'total_price'    => $totalPrice,
            'payment_status' => 'pending',
        ]);

        return redirect()->route('booking.payment', $booking);
    }

    public function payment(Booking $booking)
    {
        $service = Service::all();

        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $orderId = 'BOOKING-' . $booking->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $booking->total_price,
            ],
            'customer_details'    => [
                'first_name' => $booking->customer_name,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $booking->update(['snap_token' => $snapToken]);

        return view('booking.payment', compact('booking', 'snapToken', 'service'));
    }

    public function handleCallback(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Callback:', $payload);

        $orderId           = $payload['order_id'] ?? null;
        $statusCode        = $payload['status_code'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;

        if (! $orderId) {
            return response()->json(['status' => 'error', 'message' => 'Invalid order ID'], 400);
        }

        $bookingId = explode('-', $orderId)[1];
        $booking   = Booking::find($bookingId);

        if (! $booking) {
            return response()->json(['status' => 'error', 'message' => 'Booking not found'], 404);
        }

        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            $booking->update(['payment_status' => 'success']);
        } elseif (in_array($transactionStatus, ['pending', 'authorize'])) {
            $booking->update(['payment_status' => 'pending']);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire', 'failure'])) {
            $booking->update(['payment_status' => 'failed']);
        }

        return response()->json(['status' => 'success']);
    }

    public function success(Request $request)
    {
        $booking = Booking::latest()->first();
        return view('booking.success', compact('booking'));
    }
}
