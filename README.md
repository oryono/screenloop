# Symfony 7 Application

## Introduction

This application is built using Symfony 7 and uses JWT (JSON Web Tokens) for authentication. It is configured to use PostgreSQL as the database. This document will guide you through the setup process and provide documentation for the available API endpoints.

## Setup Instructions

### Prerequisites

- Docker
- Docker Compose

### Step 1: Clone the Repository

```bash
git clone git@github.com:oryono/screenloop.git
cd screenloop

```

### Step 2: Build the dependencies and run app
```bash
docker-compose up --build
```

### Step 3: Run migrations and seeders

```bash
./bin/migrate.sh
./bin/seed.sh
```

## Testing


## API Documentation
### Authentication
### Login

#### Endpoint: POST `/api/login`
Body
```json
{
  "email": "patrick.oryono@gmail.com",
  "password": "football"
}
```

#### Successful Response
```json
{
  "token": "your_jwt_token"
}
```

#### Failed Response
```json
{
  "message": "Invalid login credentials"
}
```

### Products

#### Endpoint: POST `GET /api/products?page=1&limit=10`
Get paginated list of products

#### Headers
```
Authorization: Bearer your_jwt_token
Accept: application/json
```

#### Successful Response
```json
[
  {
    "id": 1,
    "name": "Product 1",
    "description": "Description of Product 1",
    "price": 100.0,
    "date_of_manufacture": "2023-01-01T00:00:00+00:00",
    "expiry_date": "2024-12-31T00:00:00+00:00",
    "created_at": "2023-01-01T00:00:00+00:00",
    "updated_at": "2023-01-01T00:00:00+00:00"
  },
  ...
]
```

#### Failed Response

```json
{
  "message": "Invalid login credentials"
}
```

#### Endpoint: GET `GET /api/products/{id}`
Get single product

#### Headers
```
Authorization: Bearer your_jwt_token
Accept: application/json
```

#### Successful Response(200)
```json
{
    "id": 1,
    "name": "Product 1",
    "description": "Description of Product 1",
    "price": 100.0,
    "date_of_manufacture": "2023-01-01T00:00:00+00:00",
    "expiry_date": "2024-12-31T00:00:00+00:00",
    "created_at": "2023-01-01T00:00:00+00:00",
    "updated_at": "2023-01-01T00:00:00+00:00"
}
```

#### Endpoint: POST `POST /api/products`
Create product

#### Headers
```
Authorization: Bearer your_jwt_token
Accept: application/json
```

Body
```json
{
  "name": "product 11",
  "description": "some",
  "price": 10,
  "expiry_date": "2024-10-31",
  "date_of_manufacture": "2024-04-30"
}
```

#### Successful Response (201)
```json
{
    "id": 11,
    "name": "product 11",
    "description": "some",
    "price": 10,
    "expiry_date": "2024-10-31",
    "date_of_manufacture": "2024-04-30"
}
```

### Failed response(400)

```json
{
  "errors": [
    "description": [
      ""
    ]
  ]
}
```

#### Endpoint: POST `POST /api/products/{id}/edit`
Update product

#### Headers
```
Authorization: Bearer your_jwt_token
Accept: application/json
```

Body
```json
{
  "expiry_date": "2024-10-31",
  "date_of_manufacture": "2024-04-30"
}
```

#### Successful Response (200)
```json
{
    "id": 1,
    "name": "product 11",
    "description": "some",
    "price": 10,
    "expiry_date": "2024-10-31",
    "date_of_manufacture": "2024-04-30"
}
```

### Failed response (400)
```json

```

#### Endpoint: DELETE `POST /api/products/{id}`
Delete product

#### Headers
```
Authorization: Bearer your_jwt_token
Accept: application/json
```

### Successful request (204)


### Failed response






















