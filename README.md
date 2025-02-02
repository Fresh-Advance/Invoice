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
* PDF Invoice can be automatically generated and attached to user and owner order confirmation emails
  * Invoice filename format configurable through settings
* Total sum shown in words in the invoice.

## Limitations

* Only Twig shop installations supported
* Tested with:
  * Shop 7.1 - PHP 8.1, 8.2, MySQL 5.7 and 8.0
  * Shop 7.2 - PHP 8.2, 8.3, MySQL 5.7 and 8.0

## Branch compatibility

* Branch b-7.0.x is compatible with OXID Shop compilation 7.0.0-rc.2 and up
* Branch b-7.1.x is compatible with OXID Shop compilation 7.1.0 and up

Note: Not all latest features are available in the older branches.

## What to expect in next versions

* Show Vat for every product in list
* The Credit note issuing functionality
* Possibility to send generated invoice or credit note by email with button click.
* Other improvements? (feel free to ask in the Issues section for possible additional functions)

## Installation

Module is available on packagist and installable via composer

```
composer require fresh-advance/invoice
```

# Development installation

To be able running the tests and other preconfigured quality tools, please install the module as a [root package](https://getcomposer.org/doc/04-schema.md#root-package).

The next section shows how to install the module as a root package by using the [Fresh Advance Development Base](https://github.com/Fresh-Advance/development).

In case of different environment usage, please adjust by your own needs.

# Development installation on Fresh Advance Development Base

The installation instructions below are shown for the current [Fresh Advance Development Base](https://github.com/Fresh-Advance/development)
for shop 7.0. Make sure your system meets the requirements of the Development Base.

0. Ensure all docker containers are down to avoid port conflicts

1. Clone the SDK for the new project
```shell
echo MyProject && git clone https://github.com/Fresh-Advance/development.git $_ && cd $_
```

2. Clone the repository to the source directory
```shell
git clone --recurse-submodules https://github.com/Fresh-Advance/Invoice.git --branch=b-7.1.x ./source
```

3. Run the recipe to setup the development environment
```shell
./source/recipes/setup-development.sh
```

You should be able to access the shop with http://localhost.local and the admin panel with http://localhost.local/admin
(credentials: noreply@oxid-esales.com / admin)

### Running the tests and quality tools

Check the "scripts" section in the `composer.json` file for the available commands. Those commands can be executed
by connecting to the php container and running the command from there, example:

```shell
make php
composer tests-coverage
```

Commands can be also triggered directly on the container with docker compose, example:

```shell
docker compose exec -T php composer tests-coverage

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

### More information you might need for the template

The order variable in the template is the Order model object, so you can access all its fields and methods.

Some examples you might need for achieving the desired result:

* Customer number: {{ order.getOrderUser().getFieldData('oxcustnr') }}
* Payment method: {{ order.getPaymentType().oxpayments__oxdesc.value }}
* Order net sum: {{ order.getOrderNetSum() }}

## License

Please make sure you checked the License before using the module. License
subscription can be bought on [MB Arbatos Klubas website](https://arbatosklubas.eu/)
