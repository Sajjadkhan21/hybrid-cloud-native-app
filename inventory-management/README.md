# Inventory Management System

A simple inventory management application built with **PHP, MySQL, HTML, CSS, and JavaScript**. It helps users manage product records, monitor stock levels, and track inventory value.

## Features

- Add, edit, and delete product records
- Manage product names, SKU, categories, prices, and quantities
- Search and filter products
- Monitor low-stock and out-of-stock products
- View total products and inventory value
- Database settings read from environment variables, so the same code runs locally, in Docker, and in Kubernetes

## Project Structure

```text
inventory-management/
├── config/
│   └── database.php
├── api/
│   └── products.php
├── assets/
│   ├── style.css
│   └── app.js
├── database.sql
├── index.php
└── Dockerfile
```

## Configuration

The application uses the following environment variables:

| Variable | Description |
|---|---|
| DB_HOST | MySQL server hostname |
| DB_NAME | Database name |
| DB_USER | Database username |
| DB_PASS | Database password |

## Build Docker Image

```bash
docker build -t inventory-management:latest .
```

## Run with Docker

```bash
docker run -d \
  --name inventory-app \
  --network cloud-spark \
  --env-file ../.env \
  -e DB_NAME=inventory_management \
  -p 8081:80 \
  inventory-management:latest
```

Open the application in your browser:

`http://localhost:8081`

## Build a Versioned Image

```bash
docker build -t inventory-management:1.0.0 .
```

## Author

Cloud Spark Project Team
