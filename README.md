# Uso

Todas las peticiones deben tener los encabezados:
- `Accept: application/json`
- `Content-Type: application/json`

### Crear Password Grant Client 

Cada servicio que vaya a tener verificación de usuarios debe tener asignado un `client_id` y un `client_secret`, los cuales se generan mediante: `php artisan passport:client --password`.

## Register

```shell
$ curl -X POST http://localhost:8000/api/user \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{
      "name": "damian",
      "email": "damianesmoris@gmail.com",
      "password": "aa",
      "password_confirmation": "aa"
    }'
```

```json
{
  "name": "damian",
  "email": "damianesmoris@gmail.com",
  "updated_at": "2025-06-21T15:55:49.327000Z",
  "created_at": "2025-06-21T15:55:49.327000Z",
  "id": 56
}
```

## Login

```shell
$ curl -X POST http://localhost:8000/oauth/token \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{
      "grant_type": "password",
      "client_id": "(ID_SECRET_API)",
      "client_secret": "(CLIENT_SECRET_API)",
      "username": "damianesmoris@gmail.com",
      "password": "aa"
    }'
```

```json
{
  "token_type": "Bearer",
  "expires_in": (NUMERITOS),
  "access_token": "(TOKEN)",
  "refresh_token": "(TOKEN)"
}
```

## Validar token de acceso *(login automático)*

```shell
$ curl -X GET http://localhost:8000/api/validate \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer (ACCESS_TOKEN_DEL_LOGIN)"
```

```json
{
  "id": 1,
  "name": "damian",
  "email": "damianesmoris@gmail.com",
  "email_verified_at": null,
  "created_at": "2025-06 -21T18:28:01.720000Z",
  "updated_at": "2025-06-21T18:28:01.720000Z"
}
```
o
```json
{"message":"Unauthenticated."}
```


## Logout

```shell
$ curl -X GET http://localhost:8000/api/logout \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer (ACCESS_TOKEN_DEL_LOGIN)"
```

