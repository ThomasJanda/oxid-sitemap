<?php

$sMetadataVersion = '2.0';

$aModule = array(
    'id'          => 'rs-sitemap',
    'title'       => '*RS Sitemap',
    'description' => '',
    'thumbnail'   => '',
    'version'     => '1.0.0',
    'author'      => '',
    'url'         => '',
    'email'       => '',
    'controllers' => array(
        'rs_sitemap_generator' => \rs\sitemap\Application\Controller\Admin\rs_sitemap_generator::class,

    ),
    'extend'      => array(
    ),
    'templates' => array(
        'rs_sitemap_generator.tpl'   => 'rs/sitemap/views/admin/tpl/rs_sitemap_generator.tpl',
    ),
    'blocks'      => array(
    ),
    'settings'    => array(
        array(
            'group' => 'rs-sitemap_main',
            'name'  => 'rs-sitemap_main_language',
            'type'  => 'str',
            'value' => '0|1',
        ),

        array(
            'group' => 'rs-sitemap_shop',
            'name'  => 'rs-sitemap_shop_enable',
            'type'  => 'bool',
            'value' => true,
        ),
        array(
            'group' => 'rs-sitemap_shop',
            'name'  => 'rs-sitemap_shop_frequence',
            'type'  => 'str',
            'value' => 'weekly',
        ),
        array(
            'group' => 'rs-sitemap_shop',
            'name'  => 'rs-sitemap_shop_prio',
            'type'  => 'str',
            'value' => '0.8',
        ),

        array(
            'group' => 'rs-sitemap_category',
            'name'  => 'rs-sitemap_category_enable',
            'type'  => 'bool',
            'value' => true,
        ),
        array(
            'group' => 'rs-sitemap_category',
            'name'  => 'rs-sitemap_category_frequence',
            'type'  => 'str',
            'value' => 'monthly',
        ),
        array(
            'group' => 'rs-sitemap_category',
            'name'  => 'rs-sitemap_category_prio',
            'type'  => 'str',
            'value' => '0.3',
        ),
        array(
            'group' => 'rs-sitemap_category',
            'name'  => 'rs-sitemap_category_pic',
            'type'  => 'bool',
            'value' => true,
        ),

        array(
            'group' => 'rs-sitemap_manufacturer',
            'name'  => 'rs-sitemap_manufacturer_enable',
            'type'  => 'bool',
            'value' => true,
        ),
        array(
            'group' => 'rs-sitemap_manufacturer',
            'name'  => 'rs-sitemap_manufacturer_frequence',
            'type'  => 'str',
            'value' => 'monthly',
        ),
        array(
            'group' => 'rs-sitemap_manufacturer',
            'name'  => 'rs-sitemap_manufacturer_prio',
            'type'  => 'str',
            'value' => '0.3',
        ),
        array(
            'group' => 'rs-sitemap_manufacturer',
            'name'  => 'rs-sitemap_manufacturer_pic',
            'type'  => 'bool',
            'value' => true,
        ),

        array(
            'group' => 'rs-sitemap_content',
            'name'  => 'rs-sitemap_content_enable',
            'type'  => 'bool',
            'value' => true,
        ),
        array(
            'group' => 'rs-sitemap_content',
            'name'  => 'rs-sitemap_content_frequence',
            'type'  => 'str',
            'value' => 'monthly',
        ),
        array(
            'group' => 'rs-sitemap_content',
            'name'  => 'rs-sitemap_content_prio',
            'type'  => 'str',
            'value' => '0.3',
        ),

        array(
            'group' => 'rs-sitemap_article',
            'name'  => 'rs-sitemap_article_enable',
            'type'  => 'bool',
            'value' => true,
        ),
        array(
            'group' => 'rs-sitemap_article',
            'name'  => 'rs-sitemap_article_frequence',
            'type'  => 'str',
            'value' => 'weekly',
        ),
        array(
            'group' => 'rs-sitemap_article',
            'name'  => 'rs-sitemap_article_prio',
            'type'  => 'str',
            'value' => '0.8',
        ),
        array(
            'group' => 'rs-sitemap_article',
            'name'  => 'rs-sitemap_article_pic',
            'type'  => 'bool',
            'value' => true,
        ),
        array(
            'group' => 'rs-sitemap_article',
            'name'  => 'rs-sitemap_article_variants',
            'type'  => 'bool',
            'value' => false,
        ),
        array(
            'group' => 'rs-sitemap_article',
            'name'  => 'rs-sitemap_article_stock',
            'type'  => 'bool',
            'value' => false,
        ),
    ),
);