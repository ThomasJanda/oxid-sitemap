# Oxid sitemap

## Description

Generate a sitemap you can use for google to publish all pages of your shop.
The sitemap will not update by itself. You have to regenerate it from time to time. After
publish to Google, Google will visite the sitemap by itself from time to time.

To publish the sitemap, you have to create a account on Google Search Console.

https://search.google.com/search-console

## Install

1. Copy files into following directory

        source/modules/rs/sitemap

2. Create a directory with write/read permission in the shop root called "rs_sitemap"
        
3. Add following to composer.json on the shop root

        "autoload": {
            "psr-4": {
                "rs\\sitemap\\": "./source/modules/rs/sitemap"
            }
        },
    
3. Refresh autoloader files with composer.

        composer dump-autoload
        
4. Enable module in the oxid admin area, Extensions => Modules

## How to use

If you generate a sitemap, you can find the file in the shop "#SHOP_ROOT_DIRECTORY#/rs_sitemap/1.xml".
