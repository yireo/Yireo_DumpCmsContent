# Yireo DumpCmsContent

<!-- badges.specs.start -->
![Magento version](https://img.shields.io/badge/Magento-2.4.6%20%7C%202.4.9-orange)
![PHP version](https://img.shields.io/badge/PHP-8.2%E2%80%938.5-777BB4)
![License](https://img.shields.io/badge/License-OSL--3.0-blue)
![Latest Version](https://img.shields.io/packagist/v/yireo/magento2-dump-cms-content)
<!-- badges.specs.end -->

A Magento 2 module that adds a CLI `bin/magento cms:dump` to dump all CMS pages and CMS blocks to a folder `var/cms-output`.

## Why?
This module was developed with Hyva Themes in mind: With a theme based on Hyva, your Tailwind CSS will need to be purged before going into production. For this, you would configure the Tailwind configuration to look into certain content folders to make sure CSS utility classes that are actually used, are not purged from the production CSS. Unfortunately, this does not work with the database.

Once you add Tailwind CSS classes to your CMS Blocks and CMS Pages (via the Magento Admin Panel, for instance by using some Tailwind-oriented WYSIWYG editor, or by inserting code manually), these CSS classes might or might not be purged, which could lead to incorrect CSS styling. To prevent this from happening, this module allows you to dump CMS contents to a folder `var/cms-output`, which could then be included in the Tailwind configuration:

```js
module.exports = {
    ...
    purge: {
        content: [
            ...
            '../../../../../../../var/cms-output/**/*.html'
        ]
    }
}
```

## Usage
```bash
bin/magento cms:dump
bin/magento cms:dump --include-inactive
```

By default, only active CMS blocks and CMS pages are dumped. Use `--include-inactive` to also dump disabled content.

Every file is named `cms-block-{id}-{identifier}.html` or `cms-page-{id}-{identifier}.html`. All characters of the identifier except `a-z`, `0-9`, `_` and `-` are replaced with `_`, so all files end up directly in `var/cms-output`. Entries without content are skipped.

## Security notes
- The folder `var/cms-output` is emptied on every run. Do not store anything else in it.
- The command refuses to write when `var/cms-output` is a symlink pointing elsewhere.
- The dump contains raw CMS content. Do not commit `var/cms-output` to a repository and make sure it is not publicly accessible via the webserver.

## Current status

<!-- badges.test.start -->
![Static Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_DumpCmsContent/static-tests.yml?label=static-tests)
![Unit Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_DumpCmsContent/unit-tests.yml?label=unit-tests)
![Integration Tests](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_DumpCmsContent/integration-tests.yml?label=integration-tests)
![Playwright](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_DumpCmsContent/playwright.yml?label=playwright)
![DI Compilation](https://img.shields.io/github/actions/workflow/status/yireo/Yireo_DumpCmsContent/compile.yml?label=compile)
<!-- badges.test.end -->
