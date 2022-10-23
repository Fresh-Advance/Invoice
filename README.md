# Invoice

[![Development](https://github.com/Fresh-Advance/Invoice/actions/workflows/development.yml/badge.svg?branch=b-7.0.x)](https://github.com/Fresh-Advance/Invoice/actions/workflows/development.yml)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Invoice&metric=alert_status)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Invoice)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Invoice&metric=coverage)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Invoice)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Invoice&metric=sqale_index)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Invoice)

OXID Invoice Module

## Dev section

Unit tests:
```
XDEBUG_MODE=coverage vendor/bin/phpunit \
    -c vendor/fresh-advance/invoice/tests/phpunit.xml \
    --bootstrap=source/bootstrap.php \
    --coverage-html=/var/www/Coverage \
    vendor/fresh-advance/invoice/tests
```