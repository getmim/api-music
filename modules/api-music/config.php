<?php

return [
    '__name' => 'api-music',
    '__version' => '0.0.1',
    '__git' => 'git@github.com:getmim/api-music.git',
    '__license' => 'MIT',
    '__author' => [
        'name' => 'Iqbal Fauzi',
        'email' => 'iqbalfawz@gmail.com',
        'website' => 'http://iqbalfn.com/'
    ],
    '__files' => [
        'modules/api-music' => ['install','update','remove']
    ],
    '__dependencies' => [
        'required' => [
            [
                'music' => NULL
            ]
        ],
        'optional' => []
    ],
    'autoload' => [
        'classes' => [
            'ApiMusic\\Controller' => [
                'type' => 'file',
                'base' => 'modules/api-music/controller'
            ]
        ],
        'files' => []
    ],
    'routes' => [
        'api' => [
            'apiMusicIndex' => [
                'path' => [
                    'value' => '/music'
                ],
                'method' => 'GET',
                'handler' => 'ApiMusic\\Controller\\Music::index'
            ],
            'apiMusicSingle' => [
                'path' => [
                    'value' => '/music/listen/(:identity)',
                    'params' => [
                        'identity' => 'any'
                    ]
                ],
                'method' => 'GET',
                'handler' => 'ApiMusic\\Controller\\Music::single'
            ],
            'apiMusicAlbumIndex' => [
                'path' => [
                    'value' => '/music/album'
                ],
                'method' => 'GET',
                'handler' => 'ApiMusic\\Controller\\Album::index'
            ],
            'apiMusicAlbumSingle' => [
                'path' => [
                    'value' => '/music/album/(:identity)',
                    'params' => [
                        'identity' => 'any'
                    ]
                ],
                'method' => 'GET',
                'handler' => 'ApiMusic\\Controller\\Album::single'
            ]
        ]
    ]
];