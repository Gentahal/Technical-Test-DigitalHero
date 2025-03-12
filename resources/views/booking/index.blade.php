<!DOCTYPE html>
<html>
<head>
    <title>Booking Rental</title>
</head>
<body>
    <h1>Booking Rental</h1>
    <form action="{{ route('booking.store') }}" method="POST">
        @csrf
        <label for="customer_name">Nama:</label>
        <input type="text" name="customer_name" required><br>

        <label for="booking_date">Tanggal Booking:</label>
        <input type="date" name="booking_date" required><br>

        <label for="service_id">Layanan:</label>
        <select name="service_id" required>
            @foreach ($services as $service)
                <option value="{{ $service->id }}">{{ $service->name }} - Rp {{ number_format($service->price, 0) }}</option>
            @endforeach
        </select><br>

        <button type="submit">Booking</button>
    </form>
</body>
</html>