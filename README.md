# Mini-Bank

A lightweight banking REST API built with PHP 8.2+, Slim 4, and PostgreSQL. Supports user management, multi-currency accounts, and idempotent money transfers with JWT authentication.

## Requirements

- PHP 8.2+
- PostgreSQL
- Composer

## Setup

```bash
git clone https://github.com/ErfanMomeniii/mini-bank.git
cd mini-bank
composer install
```

Set environment variables:

```bash
export DB_HOST=localhost
export DB_PORT=5432
export DB_NAME=mini_bank
export DB_USER=postgres
export DB_PASSWORD=secret
export JWT_KEY=your-secret-key
```

Run migrations and start the server:

```bash
vendor/bin/phinx migrate
php -S localhost:8080 -t public
```

## Authentication

All endpoints except user registration and login require a JWT token.

```bash
# Register a user
curl -s -X POST localhost:8080/users \
  -H 'Content-Type: application/json' \
  -d '{"phone_number": "+1234567890", "status": "Active"}'

# Login
curl -s -X POST localhost:8080/auth/login \
  -H 'Content-Type: application/json' \
  -d '{"phone_number": "+1234567890"}'
# → {"token": "eyJ...", "expires_in": 3600}

# Use the token
TOKEN=eyJ...
curl -s localhost:8080/users \
  -H "Authorization: Bearer $TOKEN"
```

## API Endpoints

### Public

| Method | Path | Description |
|--------|------|-------------|
| POST | `/users` | Register a new user |
| POST | `/auth/login` | Authenticate and receive JWT |

### Protected (require `Authorization: Bearer <token>`)

#### Users

| Method | Path | Body |
|--------|------|------|
| GET | `/users` | — |
| GET | `/users/{id}` | — |
| PUT | `/users/{id}` | `phone_number`, `status` |
| DELETE | `/users/{id}` | — |

#### Currencies

| Method | Path | Body |
|--------|------|------|
| GET | `/currencies` | — |
| POST | `/currencies` | `name`, `code`, `symbol` |
| GET | `/currencies/{id}` | — |
| PUT | `/currencies/{id}` | `name`, `code`, `symbol` |
| DELETE | `/currencies/{id}` | — |

#### Accounts

| Method | Path | Body |
|--------|------|------|
| GET | `/accounts` | — |
| POST | `/accounts` | `user_id`, `currency_id`, `balance` |
| GET | `/accounts/{id}` | — |
| PUT | `/accounts/{id}` | `status` |
| DELETE | `/accounts/{id}` | — |

#### Transactions

| Method | Path | Body |
|--------|------|------|
| GET | `/transactions` | — |
| POST | `/transactions` | `from_account_id`, `to_account_id`, `amount`, `idempotency_key` |
| GET | `/transactions/{id}` | — |

## Transfer Logic

`POST /transactions` performs an idempotent money transfer:

1. If `idempotency_key` was already used, returns the existing transaction
2. Validates both accounts are active and share the same currency
3. Checks sufficient funds on the source account
4. Creates the transaction as `Pending`, moves funds, then marks `Success`
5. On failure, marks the transaction as `Failed`


## License

MIT
