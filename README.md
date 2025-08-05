# Payment QR API

API de ejemplo para generar códigos QR, confirmar pagos y recargar saldo.

## Autenticación

La API usa tokens JWT. Para obtener un token:

```bash
curl -X POST https://localhost/login -d '{"username":"user","password":"password"}' -H 'Content-Type: application/json'
```

Usa el token recibido en el header `Authorization: Bearer <token>` para acceder a los endpoints protegidos.

## Endpoints

- `POST /qr/create` Genera un QR de cobro.
- `GET /qr/{qrId}` Obtiene detalles de un QR.
- `POST /payment/confirm` Confirma un pago.
- `POST /balance/recharge` Recarga saldo del usuario.

Las estructuras de petición y respuesta siguen la especificación OpenAPI incluida en el enunciado.
