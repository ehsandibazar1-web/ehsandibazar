<?php

/*
|--------------------------------------------------------------------------
| Documentation for this config :
|--------------------------------------------------------------------------
| online  => http://unisharp.github.io/laravel-filemanager/config
| offline => vendor/unisharp/laravel-filemanager/docs/config.md
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
     */

    'use_package_routes'   => true,
    'url_prefix' => 'filemanager',


    /*
    |--------------------------------------------------------------------------
    | Shared folder / Private folder
    |--------------------------------------------------------------------------
    |
    | If both options are set to false, then shared folder will be activated.
    |
     */

    'allow_private_folder'     => true,

    // Flexible way to customize client folders accessibility
    // If you want to customize client folders, publish tag="lfm_handler"
    // Then you can rewrite userField function in App\Handler\ConfigHandler class
    // And set 'user_field' to App\Handler\ConfigHandler::class
    // Ex: The private folder of user will be named as the user id.
    'private_folder_name'      => UniSharp\LaravelFilemanager\Handlers\ConfigHandler::class,

    'allow_shared_folder'      => true,

    'shared_folder_name'       => 'shares',

    /*
    |--------------------------------------------------------------------------
    | Folder Names
    |--------------------------------------------------------------------------
     */

    'folder_categories'        => [
        'file'  => [
            'folder_name'  => 'files',
            'startup_view' => 'grid',
            'max_size'     => 1000000, // size in KB
            'valid_mime' => [
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/gif',
                'image/svg+xml',
                'image/webp',
                'application/pdf',
                'text/plain',
                'text/csv',
                'video/mp4',
                'video/quicktime',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/docx',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
//                'application/x-htmlmail-template',
//                'application/vnd.google-earth.kml+xml kml',
//                'application/x-decomail-template',
//                'application/x-kddi-htmlmail',
//                'application/x-htmlmail-template',
//                'application/andrew-inset',
//                'application/atom+xml',
//                'application/mac-binhex40',
//                'application/mac-compactpro',
//                'application/mathml+xml',
                'application/msword',
//                'application/octet-stream',
//                'application/oda',
                'application/ogg',
                'application/pdf',
//                'application/postscript',
//                'application/rdf+xml',
//                'application/smil',
//                'application/srgs',
//                'application/srgs+xml',
//                'application/vnd.mif',
                'application/vnd.ms-excel',
                'application/vnd.ms-powerpoint',
//                'application/vnd.rn-realmedia',
//                'application/vnd.wap.wbxml',
//                'application/vnd.wap.wmlc',
//                'application/vnd.wap.wmlscriptc',
//                'application/voicexml+xml',
//                'application/x-bcpio',
//                'application/x-cdlink',
//                'application/x-chess-pgn',
//                'application/x-cpio',
//                'application/x-csh',
//                'application/x-director',
//                'application/x-dvi',
//                'application/x-futuresplash',
//                'application/x-gtar',
//                'application/x-hdf',
//                'application/xhtml+xml',
//                'application/x-ipix',
//                'application/x-ipscript',
//                'application/x-javascript',
//                'application/x-koan',
//                'application/x-latex',
                'application/xml',
//                'application/xml-dtd',
                'application/x-mpeg',
//                'application/x-netcdf',
//                'application/x-sh',
//                'application/x-shar',
//                'application/x-shockwave-flash',
                'application/xslt+xml',
//                'application/x-smaf',
//                'application/x-stuffit',
//                'application/x-sv4cpio',
//                'application/x-sv4crc',
//                'application/x-tar',
//                'application/x-tcl',
//                'application/x-tex',
//                'application/x-texinfo',
//                'application/x-troff',
//                'application/x-troff-man',
//                'application/x-troff-me',
//                'application/x-troff-ms',
//                'application/x-ustar',
//                'application/x-wais-source',
//                'application/x-xp',
                'application/zip',
                'audio/3gpp2',
                'audio/basic',
                'audio/midi',
                'audio/mpeg',
                'audio/mp3',
                'audio/x-aiff',
                'audio/x-mpegurl',
                'audio/x-ms-wma',
                'audio/x-pn-realaudio',
                'audio/x-wav',
                'audio/wav',
//                'chemical/x-pdb',
//                'chemical/x-xyz',
                'image/bmp',
//                'image/cgm',
                'image/gif',
                'image/ief',
                'image/jpeg',
                'image/png',
                'image/svg+xml',
                'image/tiff',
//                'image/vnd.djvu',
//                'image/vnd.wap.wbmp',
//                'image/x-cmu-raster',
                'image/x-icon',
//                'image/x-portable-anymap',
//                'image/x-portable-bitmap',
//                'image/x-portable-graymap',
//                'image/x-portable-pixmap',
//                'image/x-rgb',
//                'image/x-xbitmap',
//                'image/x-xpixmap',
//                'image/x-xwindowdump',
//                'model/iges',
//                'model/mesh',
//                'model/vrml',
//                'text/calendar',
//                'text/css',
//                'text/html',
                'text/plain',
                'text/richtext',
                'text/rtf',
//                'text/sgml',
//                'text/tab-separated-values',
//                'text/vnd.wap.wml',
//                'text/vnd.wap.wmlscript',
//                'text/x-hdml;charset=Shift_JIS',
//                'text/x-setext',
                'video/3gpp',
                'video/3gpp2',
                'video/mpeg',
                'video/quicktime',
                'video/vnd.mpegurl',
                'video/x-ms-asf',
                'video/x-msvideo',
                'video/x-ms-wmv',
                'video/x-sgi-movie',
                'x-conference/x-cooltalk',
                'application/octet-stream',
                'application/octet-stream ucm',
                'application/octet-stream xcsf',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document docx',
                'application/application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'image/vnd.microsoft.icon',
                'image/x-icon',
                'application/vnd.ms-excel',
                'application/docx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet xlsx',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation pptx',
                'video/x-flv',
//                'application/java-archive',
                'video/mp4',


            ],
        ],
        'image' => [
            'folder_name'  => 'photos',
            'startup_view' => 'list',
            'max_size'     => 1000000, // size in KB
            'valid_mime'   => [
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/gif',
                'image/svg+xml',
                'image/webp',
                'application/pdf',
                'text/plain',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
     */

    'paginator' => [
        'perPage' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload / Validation
    |--------------------------------------------------------------------------
     */

    'disk'                     => 'public',

    'rename_file'              => false,

    'alphanumeric_filename'    => false,

    'alphanumeric_directory'   => false,

    'should_validate_size'     => false,

    'should_validate_mime'     => false,

    // behavior on files with identical name
    // setting it to true cause old file replace with new one
    // setting it to false show `error-file-exist` error and stop upload
    'over_write_on_duplicate'  => false,

    /*
    |--------------------------------------------------------------------------
    | Thumbnail
    |--------------------------------------------------------------------------
     */

    // If true, image thumbnails would be created during upload
    'should_create_thumbnails' => true,

    'thumb_folder_name'        => 'thumbs',

    // Create thumbnails automatically only for listed types.
    'raster_mimetypes'         => [
        'image/jpeg',
        'image/pjpeg',
        'image/png',
    ],

    'thumb_img_width'          => 200, // px

    'thumb_img_height'         => 200, // px

    /*
    |--------------------------------------------------------------------------
    | File Extension Information
    |--------------------------------------------------------------------------
     */

    'file_type_array'          => [
        'pdf'  => 'Adobe Acrobat',
        'doc'  => 'Microsoft Word',
        'docx' => 'Microsoft Word',
        'xls'  => 'Microsoft Excel',
        'xlsx' => 'Microsoft Excel',
        'zip'  => 'Archive',
        'gif'  => 'GIF Image',
        'jpg'  => 'JPEG Image',
        'jpeg' => 'JPEG Image',
        'png'  => 'PNG Image',
        'ppt'  => 'Microsoft PowerPoint',
        'pptx' => 'Microsoft PowerPoint',
        'wav' => 'WAV audio',
        'mp4' => 'MP4 Video',

    ],

    /*
    |--------------------------------------------------------------------------
    | php.ini override
    |--------------------------------------------------------------------------
    |
    | These values override your php.ini settings before uploading files
    | Set these to false to ingnore and apply your php.ini settings
    |
    | Please note that the 'upload_max_filesize' & 'post_max_size'
    | directives are not supported.
     */
    'php_ini_overrides'        => [
        'memory_limit' => '256M',
    ],
];
