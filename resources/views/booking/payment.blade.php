<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4 text-center">
                    <h2 class="mb-4">Pembayaran</h2>
                    <p><strong>Nama:</strong> {{ $booking->customer_name }}</p>
                    <p><strong>Tanggal Booking:</strong> {{ $booking->booking_date }}</p>
                    <p><strong>Layanan:</strong> {{ $booking->service ? $booking->service->name : 'Layanan tidak ditemukan' }}</p>
                    <p><strong>Total Harga:</strong> Rp {{ number_format($booking->total_price, 0) }}</p>

                    <button id="pay-button" class="btn btn-success w-100">Bayar Sekarang</button>
                </div>
            </div>
        </div>
    </div>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>