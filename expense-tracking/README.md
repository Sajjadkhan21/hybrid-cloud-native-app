# Expense Tracking System

A simple expense tracking application built with **PHP, MySQL, HTML, CSS, and JavaScript**. It helps users record income and expenses, monitor their balance, and manage financial transactions.

## Features

- Add, edit, and delete income and expense records
- Manage transaction descriptions, amounts, dates, and categories
- View total income, total expenses, and current balance
- Search and filter transactions
- Filter transactions by month
- View category-wise expense breakdown
- Display financial amounts in PKR
- Database settings read from environment variables

## Project Structure

```text
expense-tracking/
├── config/
│   └── database.php
├── api/
│   └── transactions.php
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
docker build -t expense-tracking:latest .
```

## Run with Docker

```bash
docker run -d \
  --name expense-app \
  --network cloud-spark \
  --env-file ../.env \
  -e DB_NAME=expense_tracker \
  -p 8082:80 \
  expense-tracking:latest
```

Open the application in your browser:

`http://localhost:8082`

## Build a Versioned Image

```bash
docker build -t expense-tracking:1.0.0 .
```

## Author

Cloud Spark Project Team
