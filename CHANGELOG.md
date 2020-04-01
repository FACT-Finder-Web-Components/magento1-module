# Changelog
## Unreleased
### Changed
- Upgrade Web Components to version 3.13.0

### Added
- Added RECOMMENDATION to available import type to be pushed after feed is uploaded

## [v2.0.0] - 2019-12-17
### Added
- Added NG compatibility
- Added custom Curl adapter to ensure HTTP/1.1 is used

### Changed
- Upgraded Web Components version to 3.11.1
- Added support for PHP 7.0
- Drop support for PHP 5.6
- Refactored HttpClient
- Refactored TestConnection
- Refactored PushImport
- Refactored Tracking
- Refactored Product Helper
- Refactored Data Helper
- Removed Communication helper

## [v1.1.0] - 2019-10-25
### Changed
- Avoid all rewrites of Magento core classes and implement search result in a dedicated controller
- Remove `ff-navigation`
- Render category pages using Web Components
- Upgrade FACT-Finder WebComponents version to 3.8.0
- Tracking has been moved to be executed on the server side

## [v1.0.0] - 2019-07-03
### Changed
- Upgraded FACT-Finder WebComponents version to 3.4.0

### Fixed
- Fix wrong value for CURLOPT_ENCODING and add support for all encoding types.
- Fix search redirect on multistore environment.

## [v0.10.0] - 2019-04-26
### Changed
- Upgrade FACT-Finder WebComponents version to 3.2.0
- Codebase is moved to community pool

### Added
- Added possibility to set the exported images width and height

## [v0.9.0] - 2019-03-20
### Changed
- Upgrade FACT-Finder WebComponents version to 3.1

### Added
- Add `composer` information for the project
- Manage coding standard using PHPCS

## [v0.9-beta.7] - 2019-03-01
### Fixed
- Fixed header navigation works on Home page

### Changed
- Upgrade FACT-Finder WebComponents version to 3.0

## [v0.9-beta.6] - 2019-02-01
### Fixed
- Fix password validation in external url feed export available at /factfinder/export
- Fix upload feed file functionality in module configuration

### Changed
- Set ftp user password backend model to `adminhtml/system_config_backend_encrypted`.
  **IMPORTANT**: Re-entering the ftp password in the module configuration is required

## [v0.9-beta.5] - 2019-01-14
### Fixed
- Prevent decimal values in `Attributes` column to have their decimal separators removed

### Changed
- Remove 'keep-filters' parameter from module configuration

## [v0.9-beta.4] - 2018-12-20
### Fixed
- Add `special_price` to selected products attribute collection by default
- Emulate `adminhtml` area in `factfinder_export.php` shell script in order to execute `processAdminFinalPrice`
  observer of event `catalog_product_get_final_price`

## [v0.9-beta.3] - 2018-12-12
### Changed
- Upgrade FACT-Finder WebComponents to version 1.2.14

### Added
- Add possibility to configure frequency of feed file generation by Cron

## [v0.9-beta.2] - 2018-11-23
### Changed
- Added `unresolved` attribute to all components html in order to prevent rendering it before transformation into
  functional web components

### Added
- Added possibility to choose which customer group prices have to be selected at product export.
  Default option is `NOT LOGGED IN`


## [v0.9-beta.1] - 2018-11-08
### Fixed
- Remove the product limit on feed export
- Return simple product SKU as master product number when it has a relation to nonexistent configurable product

### Changed
- Test connection functionality now uses data send from form with no need to save the configuration before checking
  the connection

### Added
- Allow user to choose which visibilities should be applied to collection filter
- Divide product collection into batches in order to prevent memory exhaustion on product collection load

[v2.0.0]:      https://github.com/FACT-Finder-Web-Components/magento1-module/releases/tag/v2.0.0
[v1.1.0]:      https://github.com/FACT-Finder-Web-Components/magento1-module/releases/tag/v1.1.0
[v1.0.0]:      https://github.com/FACT-Finder-Web-Components/magento1-module/releases/tag/v1.0.0
[v0.10.0]:     https://github.com/FACT-Finder-Web-Components/magento1-module/releases/tag/v0.10.0
[v0.9.0]:      https://github.com/FACT-Finder-Web-Components/magento1-module/releases/tag/v0.9.0
[v0.9-beta.7]: https://github.com/FACT-Finder-Web-Components/magento1-module/releases/tag/v0.9-beta.7
[v0.9-beta.6]: https://github.com/FACT-Finder-Web-Components/magento1-module/releases/tag/v0.9-beta.6
[v0.9-beta.5]: https://github.com/FACT-Finder-Web-Components/magento1-module/releases/tag/v0.9-beta.5
[v0.9-beta.4]: https://github.com/FACT-Finder-Web-Components/magento1-module/releases/tag/v0.9-beta.4
[v0.9-beta.3]: https://github.com/FACT-Finder-Web-Components/magento1-module/releases/tag/v0.9-beta.3
[v0.9-beta.2]: https://github.com/FACT-Finder-Web-Components/magento1-module/releases/tag/v0.9-beta.2
[v0.9-beta.1]: https://github.com/FACT-Finder-Web-Components/magento1-module/releases/tag/v0.9-beta.1
