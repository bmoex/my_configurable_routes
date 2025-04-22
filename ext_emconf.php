<?php

$EM_CONF['my_configurable_routes'] = [
    'title' => 'Configurable Routes',
    'description' => 'Configure specific RouteEnhancers in page properties for url handling.',
    'category' => 'misc',
    'author' => 'Benjamin Serfhos',
    'author_email' => 'benjamin@serfhos.com',
    'state' => 'stable',
    'uploadFolder' => false,
    'version' => '3.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
