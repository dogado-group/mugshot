# dogado MugShot

[![Tests](https://github.com/dogado-group/mugshot/actions/workflows/test.yml/badge.svg)](https://github.com/dogado-group/mugshot/actions/workflows/test.yml)
[![Analysis](https://github.com/dogado-group/mugshot/actions/workflows/analysis.yml/badge.svg)](https://github.com/dogado-group/mugshot/actions/workflows/analysis.yml)
[![Coverage Status](https://coveralls.io/repos/github/dogado-group/mugshot/badge.svg?branch=main)](https://coveralls.io/github/dogado-group/mugshot?branch=main)
[![Total Downloads](https://poser.pugx.org/dogado/mugshot/downloads)](https://packagist.org/packages/dogado/mugshot)
[![Latest Stable Version](https://poser.pugx.org/dogado/mugshot/v/stable)](https://packagist.org/packages/dogado/mugshot)
[![License](https://poser.pugx.org/dogado/mugshot/license)](https://packagist.org/packages/dogado/mugshot)

A small service that helps you take screenshots of web pages and generate PDFs from HTML content.

## Requirements

- PHP 8.4+
- MariaDB / MySQL
- Node.js for [Puppeteer](https://pptr.dev/)

## Installation

Clone the repository and install dependencies:

```sh
git clone https://github.com/dogado-group/mugshot.git mugshot
cd mugshot
composer install --no-dev -o
```

Set up your environment and generate an application key:

```sh
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
```

Install Puppeteer:

```sh
npm install puppeteer --global
```

### Docker / Laravel Sail

The project ships with a `docker-compose.yml` for local development via [Laravel Sail](https://laravel.com/docs/sail):

```sh
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```

## Authentication

API endpoints are protected with [Laravel Sanctum](https://laravel.com/docs/sanctum). Include a Bearer token in every request:

```
Authorization: Bearer <your-token>
```

## Usage

### Capture Screenshot

```http
POST /api/v1/screenshot
Authorization: Bearer <token>
Content-Type: application/json
```

| Parameter       | Type      | Required | Description                                                    |
| :-------------- | :-------- | :------- | :------------------------------------------------------------- |
| `url`           | `string`  | yes      | URL of the page to capture (`http` / `https`)                  |
| `width`         | `integer` | no       | Viewport width in pixels                                       |
| `height`        | `integer` | no       | Viewport height in pixels                                      |
| `fullPage`      | `boolean` | no       | Capture the full scrollable page                               |
| `deviceScale`   | `integer` | no       | Device scale factor, between `1` and `3`                       |
| `quality`       | `integer` | no       | JPEG quality, between `30` and `100` (ignored for PNG)         |
| `delay`         | `integer` | no       | Seconds to wait before capturing (useful for JS-heavy pages)   |
| `fileExtension` | `string`  | no       | Output format: `jpeg` (default) or `png`                       |
| `response`      | `string`  | no       | Response mode: `inline` (default), `download`, or `json`       |

### Generate PDF

```http
POST /api/v1/pdf
Authorization: Bearer <token>
Content-Type: application/json
```

| Parameter  | Type     | Required | Description                                              |
| :--------- | :------- | :------- | :------------------------------------------------------- |
| `content`  | `string` | yes      | Raw HTML content to render as a PDF                      |
| `response` | `string` | no       | Response mode: `inline` (default) or `download`          |

### Health Check

```http
GET /api/v1/_healthz
```

Returns application health status. No authentication required.

## Development

### Running tests

```sh
composer test
# or directly
./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
