<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
</head>
<body>
    <h1>Payment</h1>
    <p>Nama: {{ $booking->customer_name }}</p>
    <p>Tanggal Booking: {{ $booking->booking_date }}</p>
    <p>Layanan: {{ $booking->service ? $booking->service->name : 'Layanan tidak ditemukan' }}</p>
    <p>Total Harga: Rp {{ number_format($booking->total_price, 0) }}</p>

    <button id="pay-button">Bayar Sekarang</button>

    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    window.location.href = '{{ route('booking.success') }}';
                },
                onPending: function(result){
                    alert("Pembayaran pending!");
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                }
            });
        };
    </script>
</body>
</html>