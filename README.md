# Link - URL Shortener API

## Introduction
Link is a Laravel-based URL shortening API that allows users to create, manage, and track shortened links. This document provides an overview of the API endpoints, request validation, and testing setup.

## Installation

### Prerequisites
- PHP 8.0+
- Composer
- MySQL or SQLite
- Laravel 9+

### Setup
1. Clone the repository:
   ```sh
   git clone https://github.com/criamond/link.git
   cd link
   ```
2. Install dependencies:
   ```sh
   composer install
   ```
3. Copy the environment file and configure it:
   ```sh
   cp .env.example .env
   ```
   Add to .env APP_DOMAIN your domian for frontend application

4. Configure your mail server in config/mail.php

5. Generate application key:
   ```sh
   php artisan key:generate
   ```
6. Run database migrations:
   ```sh
   php artisan migrate
   ```
7. Start the development server:
   ```sh
   php artisan serve
   ```
8. Add to cron service run command to delete users with unverified email 
longer that 10 days ago
   ```sh
   php artisan users:clean-unverified
   ```

## API Endpoints

### Authentication
#### Register a new user
**POST** `/api/register`
- **Request Body:**
  ```json
  {
    "name": "John Doe",
    "email": "johndoe@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }
  ```
- **Validation:**
    - `name`: required, string, max:255
    - `email`: required, unique, valid email
    - `password`: required, min:8, confirmed

#### Login
**POST** `/api/login`
- **Request Body:**
  ```json
  {
    "email": "johndoe@example.com",
    "password": "password123"
  }
  ```
- **Validation:**
    - `email`: required, valid email
    - `password`: required

### URL Shortening
#### Create a Shortened Link
**POST** `/api/links`
- **Request Body:**
  ```json
  {
    "url": "https://example.com"
  }
  ```
- **Validation:**
    - `url`: required, valid URL

#### Get All Links for a User
**GET** `/api/links/{user_id?}`
- Retrieves all links belonging to the authenticated user. In case Admin it will retrive all links
- Can be accessed without `user_id` to fetch links for the logged-in user.

#### Update a Link
**PUT** `/api/links/{id}`
- **Request Body:**
  ```json
  {
    "url": "https://new-url.com"
  }
  ```
- **Validation:**
    - `url`: required, valid URL

#### Delete a Link
**DELETE** `/api/links/{id}`
- **Validation:**
    - `id`: required, integer, exists in links table

### Password Reset
#### Request Password Reset Link
**POST** `/api/password/reset-request`
- **Request Body:**
  ```json
  {
    "email": "user@example.com"
  }
  ```
- **Validation:**
    - `email`: required, valid email, exists in users table

#### Reset Password
**POST** `/api/password/reset`
- **Request Body:**
  ```json
  {
    "token": "reset-token",
    "email": "user@example.com",
    "password": "newpassword",
    "password_confirmation": "newpassword"
  }
  ```
- **Validation:**
    - `token`: required, string
    - `email`: required, valid email, exists in users table
    - `password`: required, min:8, confirmed

## Validation Requests
All request validation has been moved to dedicated Laravel FormRequest classes:
- `app/Http/Requests/UpdateUserRequest.php`
- `app/Http/Requests/DestroyUserRequest.php`
- `app/Http/Requests/RegisterUserRequest.php`
- `app/Http/Requests/VerifyEmailRequest.php`
- `app/Http/Requests/LoginUserRequest.php`
- `app/Http/Requests/SendResetLinkRequest.php`
- `app/Http/Requests/ResetPasswordRequest.php`
- `app/Http/Requests/StatsRequest.php`
- `app/Http/Requests/GetAllLinksRequest.php`
- `app/Http/Requests/UpdateUserLinkRequest.php`

## Running Tests
To run unit tests, execute:
```sh
php artisan test
```

### Available Unit Tests
Unit tests are located in `tests/Feature/` and `tests/Unit/` directories. They cover:
- User registration and authentication (`tests/Feature/AuthTest.php`)
- Link creation, retrieval, updating, and deletion (`tests/Feature/LinkTest.php`)
- Password reset functionality (`tests/Feature/PasswordResetTest.php`)


