<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Berhasil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4 text-center">
                    <h2 class="mb-3 text-success">Booking Berhasil!</h2>
                    <p class="fs-5">Terima kasih telah melakukan booking.</p>
                    <p class="fw-bold">Status Pembayaran: <span class="badge bg-info">{{ $booking->payment_status }}</span></p>
                    <a href="{{ route('booking.index') }}" class="btn btn-primary mt-3">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>