# Payment QR API

API para generar códigos QR, confirmar pagos y recargar saldo.

## Docker
```bash
sudo make start
```

## Creación de usuarios admin
```bash
php bin/console app:create-default-user <email> <password>
```

## Autenticación

La API usa tokens JWT. Para obtener un token:

```bash
curl -X POST https://localhost/v1/login -d '{"username":"user","password":"password"}' -H 'Content-Type: application/json'
```

Usa el token recibido en el header `Authorization: Bearer <token>` para acceder a los endpoints protegidos.

## Endpoints

- `POST /v1/user/register` Genera un nuevo usuario.
- `POST /v1/qr/create` Genera un QR de cobro.
- `GET /v1/qr/{qrId}` Obtiene detalles de un QR.
- `POST /v1/payment/confirm` Confirma un pago.
- `POST /v1/balance/recharge` Recarga saldo del usuario.

Las estructuras de petición y respuesta siguen la especificación OpenAPI incluida en el enunciado.
