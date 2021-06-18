# Yireo DumpCmsContent
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
