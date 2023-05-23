<?php

use Serfhos\MyConfigurableRoutes\Service\TableConfigurationService;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

call_user_func(static function (string $table): void {
    ExtensionManagementUtility::addTCAcolumns($table, [
        // Detail page type
        'my_configurable_routes_type' => [
            'exclude' => true,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:my_configurable_routes/Resources/Private/Language/locallang_db.xlf:pages.my_configurable_routes_type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    // Similar to 'module' page option to display empty element if no selection is made
                    [
                        'label' => '',
                        'value' => '',
                    ],
                ],
                'default' => '',
                'itemsProcFunc' => TableConfigurationService::class . '->addPluginRouteOptions',
            ],
        ],
    ]);
    ExtensionManagementUtility::addFieldsToPalette(
        $table,
        'module',
        'my_configurable_routes_type'
    );
}, 'pages');
