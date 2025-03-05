
# Links

## Description
Links is a URL shortener service built with Laravel.

## Installation
```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## API Endpoints

### Create a link
```sh
POST /api/links
{
  "short_code": "example123",
  "url": "https://example.com"
}
```
Creates a new short link.

### Get all links
```sh
GET /api/links
```
Returns a list of all stored links.

### Get a link by short_code
```sh
GET /api/links/{short_code}
```
Retrieves data for a specific link.

### Update a link
```sh
PUT /api/links/{short_code}
{
  "url": "https://new-example.com"
}
```
Updates the URL of an existing short link.

### Delete a link
```sh
DELETE /api/links/{short_code}
```
Deletes a short link.

### Redirect to full URL
```sh
GET /{short_code}
```
Redirects the user to the full URL associated with the short code.

## Testing
```sh
php artisan test
```
