# PDF Invoice module for OXID eShop

[![Development](https://github.com/Fresh-Advance/Invoice/actions/workflows/trigger.yml/badge.svg?branch=b-7.0.x)](https://github.com/Fresh-Advance/Invoice/actions/workflows/trigger.yml)
[![Latest Version](https://img.shields.io/packagist/v/Fresh-Advance/Invoice?logo=composer&label=latest&include_prereleases&color=orange)](https://packagist.org/packages/Fresh-Advance/Invoice)
[![PHP Version](https://img.shields.io/packagist/php-v/Fresh-Advance/Invoice)](https://github.com/Fresh-Advance/Invoice)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Invoice&metric=alert_status)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Invoice)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Invoice&metric=coverage)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Invoice)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Invoice&metric=sqale_index)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Invoice)

## Features

* Twig and Smarty shop installations supported
* PDF Invoice file generated in shop Default language
  * Currently we have DE, EN and LT translations available here. Feel free to add yours.
  * Total sum shown in words in the invoice.
* Several fields are adjustable before invoice generation:
  * Invoice number
  * Invoice date
  * Invoice signer person
* Works with php 8.0 and 8.1
* Mysql 5.7 and 8.0 supported

## Compatibility

* Branch b-7.0.x is compatible with OXID Shop compilation 7.0.0-rc.2 and up

## What to expect in next versions

* New blocks in templates for easy extending
* Show Vat for every product in list
* Other improvements

## Installation

Module is available on packagist and installable via composer

```
composer require fresh-advance/invoice
```

## License

Please make sure you checked the License before using the module.
