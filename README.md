# dogado MugShot

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dogado/mugshot.svg?style=flat-square)](https://packagist.org/packages/dogado/mugshot)

A small service that helps you take screenshots of web pages.

## Requirements

- PHP 8
- MySQL
- NodeJS for [Puppeteer](https://pptr.dev/)

## Installation

```sh
git clone https://github.com/dogado/mugshot.git mugshot
composer install --no-dev -o

php artisan key:generate
php artisan migrate
php artisan storage:publish
```

Mugshot needs puppeteer to capture screenshots

```sh
npm install puppeteer --global
```

## Usage

### Capture Screenshot

```http
  GET /api/v1/screenshot
```

| Parameter          | Type       | Description                |
| :----------------- | :--------- | :------------------------- |
| `url`              | `string`   | `URL of the page you want to capture` |
| `width`            | `integer`  |  |
| `height`           | `integer`  |  |
| `fullPage`         | `boolean`  |  |
| `deviceScale`      | `integer`  |  |
| `quality`          | `integer`  |  |
| `delay`            | `integer`  |  |
| `fileExtension`     | `string`   |  |
| `response`         | `string`   |  |

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
