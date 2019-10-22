<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Configurable Routes',
    'description' => 'Configure static routes in backend for url handling',
    'category' => 'misc',
    'author' => 'Benjamin Serfhos',
    'author_email' => 'benjamin@serfhos.com',
    'state' => 'stable',
    'uploadFolder' => false,
    'clearCacheOnLoad' => true,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
