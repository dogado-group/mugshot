# dogado MugShot

[![Tests](https://github.com/dogado-group/mugshot/actions/workflows/test.yml/badge.svg)](https://github.com/dogado-group/mugshot/actions/workflows/test.yml)
[![Coverage Status](https://coveralls.io/repos/github/dogado-group/mugshot/badge.svg?branch=main)](https://coveralls.io/github/dogado-group/mugshot?branch=main)
[![Total Downloads](https://poser.pugx.org/dogado/mugshot/downloads)](https://packagist.org/packages/dogado/mugshot)
[![Latest Stable Version](https://poser.pugx.org/dogado/mugshot/v/stable)](https://packagist.org/packages/dogado/mugshot)
[![License](https://poser.pugx.org/dogado/mugshot/license)](https://packagist.org/packages/dogado/mugshot)

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

| Parameter           | Type       | Description                                            |
| :------------------ | :--------- | :----------------------------------------------------- |
| `url`               | `string`   | URL of the page you want to capture                    |
| `width`             | `integer`  | Width of the screenshot                                |
| `height`            | `integer`  | Height of the screenshot                               |
| `fullPage`          | `boolean`  | Allows you to capture the entire page                  |
| `deviceScale`       | `integer`  | Between 1 and 3                                        |
| `quality`           | `integer`  | Only works with JPG, uses percent                      |
| `delay`             | `integer`  | Wait in seconds before taking a screenshot of the page |
| `fileExtension`     | `string`   | `PNG` or `JPG`                                         |
| `response`          | `string`   | `inline`, `download`, `json`                           |

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
