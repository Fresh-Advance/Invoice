# Change Log for Fresh-Advance Invoice module.

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [v5.0.0-rc.1] - Unreleased

### Changed
- Renamed the `Document` namespace to `Pdf`

## [v4.1.0] - 2025-05-12

### Added
- Show Sales Tax ID in the invoice buyer column if configured

### Changed
- Updated quality tools - phpunit 10->11, phpstan 1.8->2.1, codeception 5.0->5.1

### Fixed
- Small coding style issues
- Shop version conflicts in composer.json
- Cleanup unused smarty template pointers in metadata.php

## [v4.0.0] - 2025-02-18

### Added
- Changes from [v4.0.0-rc.1] and [v4.0.0-rc.2]
- Help box for Invoice number format setting

## [v4.0.0-rc.2] - 2025-02-16

### Changed
- Change filename prefix for admin panel invoice download to be format with some possible placeholders instead
- Do not show zero value Payment charge costs in the invoice
- Moved new FilenameCalculator class to Document domain, as they are used not only for emails now
- InvoiceConfigurationRepositoryInterface::getByOrderId doesnt return null anymore

### Removed
- Not used methods leftovers from ModuleSettings

### Fixed
- Updated the phpunit and other testing tools to fit 7.1 shop dependencies

## [v4.0.0-rc.1] - 2025-02-09

### Added
- New settings for configuring the invoice filename **format** in the order confirmation email for both owner and customer cases.
- New settings for turning on the invoice attachment for customer and owner cases.
- Support netto prices with separate VATs for delivery and payment costs, respecting the shop's configuration.

### Changed
- Moved classes to better fitting domains:
  - `FreshAdvance\Invoice\Transition\Model\OrderArticle` to `FreshAdvance\Invoice\Document\Model\OrderArticleExtension`
  - `FreshAdvance\Invoice\Transition\Core\Email` to `FreshAdvance\Invoice\Email\Core\EmailExtension`
  - `FreshAdvance\Invoice\Service\OrderServiceInterface` to `FreshAdvance\Invoice\Order\Service\OrderServiceInterface`
  - `FreshAdvance\Invoice\Repository\OrderRepositoryInterface` to `FreshAdvance\Invoice\Order\Repository\OrderRepositoryInterface`
- Email configuration settings moved to the `FreshAdvance\Invoice\Email\Settings\EmailSettingsInterface`

### Removed
- PHP 8.0 support

## [v3.1.0] - 2024-10-01

### Added
- New setting for configuring the filename of the invoice document in the order confirmation email

## [v3.0.1] - 2024-09-28

### Fixed
- Fix the issue with the invoice filename in the email - now its "invoice.pdf" instead of "example.pdf"

## [v3.0.0] - 2024-09-26

### Added
- Logo updated
- Layout settings for invoice document - Document margins are adjustable
- Possibility to add document Header (and there was a footer already, but to be handled through @page css)
- Possibility to automatically increase the Invoice number for the Order on invoice generation
- Possibility to set a template for Invoice number and use invoice number from the Order
- Blocks in body.html.twig for customizing the template by your needs
- Possibility to automatically calculate the current date by specific format from the settings
- Double-check question for Regeneration of the document
- New block for showing if document already generated with Download button

### Changed
- Invoice document footer setting moved to Layout settings group
- Improve quality tools configurations
- In admin controller there is no separate Save action anymore, it saves And generates the new document at once

### Fixed
- Add signer line in the invoice document only if value is not empty

### Removed
- Smarty support

## [v2.1.0] - 2023-11-08

### Added
- Show total amount in words in the invoice

### Changed
- Language functionality have been moved to separate (Language) namespace
- Cleanup too much visibility on autowired utility interfaces that should not be exposed

## [v2.0.0] - 2023-10-07

### Added
- Php 8.1 and Mysql 8.0 supported
- Possibility to generate PDFA format invoice documents

### Changed
- BC Break: Inverted dependencies on most of the classes to extracted interfaces
- BC Break: Heavily refactored most of the classes

### Fixed
- Use Symfony filesystem utility in place of Webmozart
- Do not use shop namespaces for module services
- Invoice generation if invoice language does not exist on admin side

## [v1.3.0] - 2023-03-06

### Added
- Show company VAT Id in the invoice buyer column

## [v1.2.0] - 2023-03-04

### Added
- Show Discounts, Voucher discounts and VATs in totals column

## [v1.1.0] - 2023-02-28

### Added
- Twig shop installation support
- Migrations trigger during module activation
- Invoice is now downloaded with dynamic filename (invoice number is included)
- Downloaded file name prefix is configurable in settings
- Currency shown near prices in the invoice
- Footer for invoice document is modifiable in module settings
- Delivery cost shown in the invoice

### Fixed
- In case title is not overwritten for varriant, parent title is now taken

## [v1.0.0] - 2022-11-16

### Added
- New tab in admin for Order - Invoice
- PDF file generated from order data with possibility to adjust some of the fields and regenerate the invoice file
- Invoice generated in Shop's main language (if translation available)

[v5.0.0-rc.1]: https://github.com/Fresh-Advance/Invoice/compare/v4.1.0...v5.0.0-rc.1
[v4.1.0]: https://github.com/Fresh-Advance/Invoice/compare/v4.0.0...v4.1.0
[v4.0.0]: https://github.com/Fresh-Advance/Invoice/compare/v3.1.0...v4.0.0
[v4.0.0-rc.2]: https://github.com/Fresh-Advance/Invoice/compare/v4.0.0-rc.1...v4.0.0-rc.2
[v4.0.0-rc.1]: https://github.com/Fresh-Advance/Invoice/compare/v3.1.0...v4.0.0-rc.1
[v3.1.0]: https://github.com/Fresh-Advance/Invoice/compare/v3.0.1...v3.1.0
[v3.0.1]: https://github.com/Fresh-Advance/Invoice/compare/v3.0.0...v3.0.1
[v3.0.0]: https://github.com/Fresh-Advance/Invoice/compare/v2.2.0...v3.0.0
[v2.2.0]: https://github.com/Fresh-Advance/Invoice/compare/v2.1.0...v2.2.0
[v2.1.0]: https://github.com/Fresh-Advance/Invoice/compare/v2.0.0...v2.1.0
[v2.0.0]: https://github.com/Fresh-Advance/Invoice/compare/v1.3.0...v2.0.0
[v1.3.0]: https://github.com/Fresh-Advance/Invoice/compare/v1.2.0...v1.3.0
[v1.2.0]: https://github.com/Fresh-Advance/Invoice/compare/v1.1.0...v1.2.0
[v1.1.0]: https://github.com/Fresh-Advance/Invoice/compare/v1.0.0...v1.1.0
[v1.0.0]: https://github.com/Fresh-Advance/Invoice/compare/6e6618ba66...v1.0.0
