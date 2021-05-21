# dogado MugShot

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dogado/mugshot.svg?style=flat-square)](https://packagist.org/packages/dogado/mugshot)
[![Tests](https://github.com/dogado-group/mugshot/actions/workflows/test.yml/badge.svg)](https://github.com/dogado-group/mugshot/actions/workflows/test.yml)

A small service that helps you take screenshots of web pages.

## Requirements

- PHP 8
- MySQL
- Node for [Puppeteer](https://pptr.dev/)

## Installation

```sh
git clone https://github.com/dogado/mugshot.git mugshot
composer install --no-dev -o

php artisan key:generate
php artisan migrate
php artisan storage:link
```

Mugshot uses Puppeteer in the background to create screenshots

```sh
npm install puppeteer --global
```

## Usage

### Capture Screenshot

```http
POST /api/v1/screenshot
```

| Parameter           | Type       | Description                |
| :------------------ | :--------- | :------------------------- |
| `url`               | `string`   | URL of the page you want to capture |
| `width`             | `integer`  | Width of the Picture |
| `height`            | `integer`  | Height of the Picture |
| `fullPage`          | `boolean`  | Captures the entire page and, ignores width and height |
| `deviceScale`       | `integer`  | Between 1 and 3  |
| `quality`           | `integer`  | Only works with JPG, uses percent |
| `delay`             | `integer`  | Wait in seconds before taking a picture of the page |
| `fileExtension`     | `string`   | `PNG` or `JPG`  |
| `response`          | `string`   | `inline`, `download`, `json` |

### Status Check

```http
GET /api/v1/status
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
