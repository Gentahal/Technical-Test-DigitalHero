# Booking System - Technical Test

Welcome to the Booking System repository! This project is designed to showcase my skills in Laravel development, Midtrans payment integration, and efficient booking management.

## 🚀 Features
- **Service Booking**: Customers can book services with a dynamic pricing system (weekend surcharge applied).
- **Midtrans Payment Integration**: Secure online payments with Midtrans Snap.
- **Real-time Payment Status Update**: Transactions are automatically updated upon successful payment.
- **Logging & Debugging**: Implemented logging to track payment callbacks and system behavior.

## 🛠️ Installation
1. Clone the repository:
   ```sh
   git clone https://github.com/Gentahal/Technical-Test-DigitalHero.git
   cd booking-system
   ```
2. Install dependencies:
   ```sh
   composer install
   npm install && npm run dev
   ```
3. Set up environment variables:
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```
4. Configure database in `.env` and run migrations:
   ```sh
   php artisan migrate --seed
   ```
5. Set up Midtrans credentials in `.env`:
   ```sh
   MIDTRANS_SERVER_KEY=your-server-key
   MIDTRANS_CLIENT_KEY=your-client-key
   ```
6. Serve the application:
   ```sh
   php artisan serve
   ```

## 🔗 Midtrans Integration
- Transactions are processed via **Midtrans Snap API**.
- Payment status updates are handled via **Midtrans Callbacks**.
- Snap tokens are generated dynamically and stored in the database.

## 🧪 Testing
- Run PHPUnit tests:
  ```sh
  php artisan test
  ```
- Use **Ngrok** (for local development) to receive Midtrans callbacks:
  ```sh
  ngrok http 8000
  ```

## 📩 Contact
For any questions or further discussions, feel free to reach out via [LinkedIn](https://www.linkedin.com/in/genta-halilintar) or email at gentahalilintar36@gmail.com.

---
✨ *This project is part of a technical test to demonstrate expertise in Laravel, payments, and real-world web application development.*

