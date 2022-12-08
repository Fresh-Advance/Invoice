# Invoice

[![Development](https://github.com/Fresh-Advance/Invoice/actions/workflows/development.yml/badge.svg?branch=b-7.0.x)](https://github.com/Fresh-Advance/Invoice/actions/workflows/development.yml)
[![Latest Version](https://img.shields.io/packagist/v/Fresh-Advance/Invoice?logo=composer&label=latest&include_prereleases&color=orange)](https://packagist.org/packages/Fresh-Advance/Invoice)
[![PHP Version](https://img.shields.io/packagist/php-v/Fresh-Advance/Invoice)](https://github.com/Fresh-Advance/Invoice)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Invoice&metric=alert_status)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Invoice)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Invoice&metric=coverage)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Invoice)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Invoice&metric=sqale_index)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Invoice)

OXID PDF Invoice Module

## Features

* PDF Invoice file generated in shop Default language (Check if module translations for that language are available)
* Several fields are adjustable before invoice generation:
  * Invoice number
  * Invoice date
  * Invoice signer person
* Currently smarty version of the shop is supported only

## Compatibility

* Branch b-7.0.x is compatible with OXID Shop compilation 7.0.0-rc.2 and up

## What to expect in next versions

* New blocks in templates for easy extending
* Twig shop support
* VAT listed in pdf
* Discounts listed in pdf
* Delivery listed in pdf
* Other improvements

## Installation

Module is available on packagist and installable via composer

```
composer require fresh-advance/invoice 
```

## License

Please make sure you checked the License before using the module.
