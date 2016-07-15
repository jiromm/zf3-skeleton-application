<?php

namespace Settings;

use Settings\Factory\AbstractControllerFactory;
use Settings\Factory\AbstractServiceFactory;

return [
    'controllers' => [
        'abstract_factories' => [
            AbstractControllerFactory::class,
        ]
    ],

    'service_manager' => [
        'aliases' => [
            'ss' => AbstractServiceFactory::class
        ],
        'abstract_factories' => [
            AbstractServiceFactory::class,
        ],
    ],

    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy'
        ],
    ],
];
