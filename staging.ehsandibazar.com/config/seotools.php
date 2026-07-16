<?php

return [
    'meta'      => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'        => false, // set false to total remove
            'description'  => config('app.name'), // set false to total remove
            'separator'    => ' - ',
            'keywords'     => [config('app.name')],
            'canonical'    => false, // Set null for using Url::current(), set false to total remove
        ],

        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
        ],
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => config('app.name'), // set false to total remove
            'description' => config('app.name'), // set false to total remove
            'url'         => null, // Set null for using Url::current(), set false to total remove
            'type'        => 'website',
            'site_name'   => false,
            'images'      => [config('app.url').'/storage/photos/1/theme/bg-about.png'],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            'title'       => config('app.name'), // set false to total remove
            'description' => config('app.name'), // set false to total remove
            'card'       => 'summary_large_image',
         'site'       => 'ehsandibazar',
           'image'      => config('app.url').'/storage/photos/1/theme/bg-about.png',
            'creator'    => config('app.name'),
        ],
    ],
];
