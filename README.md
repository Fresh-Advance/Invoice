# Invoice
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