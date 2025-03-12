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

        // Ambil data layanan yang dipilih
        $service = Service::find($request->service_id);

        // Hitung total harga
        $totalPrice = $service->price;

        // Weekend surcharge (Sabtu = 6, Minggu = 7)
        if (in_array(date('N', strtotime($request->booking_date)), [6, 7])) {
            $totalPrice += 50000; // Tambahan Rp 50.000 untuk weekend
        }

        // Simpan data booking
        $booking = Booking::create([
            'customer_name'  => $request->customer_name,
            'booking_date'   => $request->booking_date,
            'service_id'     => $request->service_id,
            'total_price'    => $totalPrice,
            'payment_status' => 'pending', // Status awal pembayaran
        ]);

        // Redirect ke halaman pembayaran
        return redirect()->route('booking.payment', $booking);
    }

    public function payment(Booking $booking)
    {
        $service = Service::all();
        // Konfigurasi Midtrans
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        // Buat order_id yang unik
        $orderId = 'BOOKING-' . $booking->id . '-' . time();

        // Parameter untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $booking->total_price,
            ],
            'customer_details'    => [
                'first_name' => $booking->customer_name,
            ],
        ];

        // Generate Snap Token
        $snapToken = Snap::getSnapToken($params);

        // Simpan snap token ke booking (opsional)
        $booking->update(['snap_token' => $snapToken]);

        return view('booking.payment', compact('booking', 'snapToken', 'service'));
    }

    public function handleCallback(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans Callback:', $payload); // Log payload untuk debugging

        // Ambil order_id dari payload
        $orderId           = $payload['order_id'];
        $statusCode        = $payload['status_code'];
        $transactionStatus = $payload['transaction_status'];

        // Cari booking berdasarkan order_id
        $booking = Booking::where('id', str_replace('BOOKING-', '', explode('-', $orderId)[0]))->first();

        if ($booking) {
            // Update status pembayaran jika transaksi berhasil
            if ($statusCode == 200 && $transactionStatus == 'settlement') {
                $booking->update(['payment_status' => 'success']);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function success(Request $request)
    {
        // Ambil booking terakhir untuk contoh (sesuaikan dengan kebutuhan)
        $booking = Booking::latest()->first();
        return view('booking.success', compact('booking'));
    }
}
