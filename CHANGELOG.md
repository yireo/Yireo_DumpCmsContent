# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.0] - 25 November 2026
### Added
- New `--include-inactive` option for `cms:dump` to also dump disabled CMS pages and blocks
- `cms:dump` now reports how many files were written
- README documents the `cms:dump` options and security notes
- PHP 8.5 support

### Changed
- `cms:dump` now only dumps active CMS pages and blocks by default
- Dump file names now combine the entity ID with a sanitized identifier (e.g. `cms-page-12-about-us.html`)
- CMS content is loaded in batches of 100 to keep memory usage low on large stores

### Removed
- PHP 8.1 support (now PHP 8.2 - 8.5)

### Fixed
- CMS identifiers containing path segments (like `../`) could write files outside `var/cms-output` (path traversal)
- The dump is refused when `var/cms-output` is a symlink
- Stale files from previous dumps are removed and CMS items without content are skipped
- Missing composer dependencies (`magento/module-cms`, `ext-pcre`, `symfony/console`) are added and `magento/framework` is restricted to `^103.0`

## [1.0.0] - 09 October 2025
### Fixed
- Copy generic CI/CD files
- Fix PHP 8.4 issue in Symfony Command
- Add success return code
- New license
- Update composer.json

