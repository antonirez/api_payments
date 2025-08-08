# Payment QR API

The **Payment QR API** allows you to:
- Generate QR codes for payments.
- Confirm completed transactions.
- Recharge a user's balance.

---

## 🚀 Quick Start with Docker
```bash
sudo make start
```

---

## 👤 Creating Admin Users
To create a default admin user, run:
```bash
php bin/console app:create-default-user <email> <password>
```

---

## 🔐 Authentication

This API uses **JWT tokens** for authentication.

**Step 1:** Request a token:
```bash
curl -X POST https://localhost/v1/login   -d '{"username":"user","password":"password"}'   -H 'Content-Type: application/json'
```

**Step 2:** Use the received token in the `Authorization` header to access protected endpoints:
```
Authorization: Bearer <token>
```

---

## 📌 Required Headers
- `api-key: <string>` — Your assigned API key.

---

## 📡 Endpoints

### User Management
- `POST /v1/user/register` — Creates a new user account.

### QR Management
- `POST /v1/qr/create` — Generates a payment QR code.
- `GET /v1/qr/{qrId}` — Retrieves QR code details.

### Payment Processing
- `POST /v1/payment/confirm` — Confirms a payment.

### Balance Management
- `POST /v1/balance/recharge` — Recharges a user's balance.

### Authentication
- `POST /v1/login` — Retrieves the authentication token (no header api-key required).

---

## 🛡 Security Notes
- Always use **HTTPS** when sending requests.
- Store your `api-key` and JWT token securely.
- Tokens have an expiration time; request a new one when needed.

---

## 📄 License
Oaro projects