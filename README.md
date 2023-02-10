# UnShort URLs

![License](https://img.shields.io/badge/license-MIT-blue.svg)

A [Flarum](http://flarum.org) extension to unshort shortened URLs (bit.ly, youtu.be, amzn.eu...).

## Features
After the user creates a new post or a new discussion, the extension replaces immediately the URL, so the post is saved into the database directly with the unshorted URL.

![Settings](https://raw.githubusercontent.com/matteociaroni/flarum-unshort-urls/master/settings.png)

## Installation

Install with composer:

```sh
composer require matteociaroni/flarum-unshort-urls
```

## Updating

```sh
composer update matteociaroni/flarum-unshort-urls
php flarum migrate
php flarum cache:clear
```

## Links

- [Packagist](https://packagist.org/packages/matteociaroni/flarum-unshort-urls)
- [GitHub](https://github.com/matteociaroni/flarum-unshort-urls)
