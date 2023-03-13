<?php

$EM_CONF['my_configurable_routes'] = [
    'title' => 'Configurable Routes',
    'description' => 'Configure specific RouteEnhancers in page properties for url handling.',
    'category' => 'misc',
    'author' => 'Benjamin Serfhos',
    'author_email' => 'benjamin@serfhos.com',
    'state' => 'stable',
    'uploadFolder' => false,
    'clearCacheOnLoad' => true,
    'version' => '1.2.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.2.0-12.2.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
