# PDF Invoice module for OXID eShop

[![Development](https://github.com/Fresh-Advance/Invoice/actions/workflows/trigger.yml/badge.svg?branch=b-7.0.x)](https://github.com/Fresh-Advance/Invoice/actions/workflows/trigger.yml)
[![Latest Version](https://img.shields.io/packagist/v/Fresh-Advance/Invoice?logo=composer&label=latest&include_prereleases&color=orange)](https://packagist.org/packages/Fresh-Advance/Invoice)
[![PHP Version](https://img.shields.io/packagist/php-v/Fresh-Advance/Invoice)](https://github.com/Fresh-Advance/Invoice)

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Invoice&metric=alert_status)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Invoice)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Invoice&metric=coverage)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Invoice)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=Fresh-Advance_Invoice&metric=sqale_index)](https://sonarcloud.io/dashboard?id=Fresh-Advance_Invoice)

## Features

* Adjustable invoice pages margins
* Adjustable invoice pages header and footer (images are possible in inlined CSS)
* Invoice numbering automation (uses the order's oxbillnr field)
* Several fields are adjustable before invoice generation:
  * Invoice number template (or number itself, if number automation is not used)
  * Invoice date (with configurable format for automatic calculation)
  * Invoice signer person
* PDF Invoice file generated in shop Default language
  * Currently we have DE, EN and LT translations available. Feel free to add yours.
* Total sum shown in words in the invoice.
* Only Twig shop installations supported
* Tested with:
  * Shop 7.0 - PHP 8.0, 8.1, MySQL 5.7 and 8.0
  * Shop 7.1 - PHP 8.1, 8.2, MySQL 5.7 and 8.0
  * Shop 7.2 - PHP 8.2, 8.3, MySQL 5.7 and 8.0

## Compatibility

* Branch b-7.0.x is compatible with OXID Shop compilation 7.0.0-rc.2 and up

## What to expect in next versions

* Possibility to automatically generate and send the invoice with order confirmation email
* Show Vat for every product in list
* The Credit note issuing functionality
* Possibility to send generated invoice or credit note by email with button click.
* Other improvements? (feel free to ask in Issues section for possible additional functions)

## Installation

Module is available on packagist and installable via composer

```
composer require fresh-advance/invoice
```

## Overwriting the template

For customizing the template, start from creating the template extension in your module
`views/twig/extensions/modules/fa_invoice/invoice/body.html.twig` with example content:

```twig
{% extends '@fa_invoice/invoice/body.html.twig' %}

{% block fa_invoice_invoice_body_order_number %}
    <div id="number">example overwrite</div>
{% endblock %}
```

Next, check the original template, and overwrite the blocks you need.

### Troubles during overwrite?

If overwrite doesnt work: first, clear the cache; second, might be the issue with module loading order.
If so, create the file `var/configuration/shops/1/template_extension_chain.yaml` with content:
```yaml
'@fa_invoice/invoice/body.html.twig':
  - oe_moduletemplate(please put your module id instead of the module template example)
```

## License

Please make sure you checked the License before using the module. License
subscription can be bought on [MB Arbatos Klubas website](https://arbatosklubas.eu/)
