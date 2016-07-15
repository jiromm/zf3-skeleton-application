<?php

namespace Settings;

use Settings\Factory\AbstractControllerFactory;
use Settings\Factory\AbstractMapperFactory;
use Settings\Factory\AbstractServiceFactory;

return [
    'controllers' => [
        'abstract_factories' => [
            AbstractControllerFactory::class,
        ]
    ],

    'service_manager' => [
        'abstract_factories' => [
            AbstractServiceFactory::class,
            AbstractMapperFactory::class,
        ],
    ],

    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy'
        ],
    ],
];
