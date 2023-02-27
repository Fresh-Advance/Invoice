# Change Log for Fresh-Advance Invoice module.

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [v1.1.0] - Unreleased

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

[v1.1.0]: https://github.com/Fresh-Advance/Invoice/compare/v1.0.0...v1.1.0
[v1.0.0]: https://github.com/Fresh-Advance/Invoice/compare/6e6618ba66...v1.0.0
