<?php

call_user_func(function (string $extension): void {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['enhancers'][\Serfhos\MyConfigurableRoutes\Routing\PluginConfigurableByPageEnhancer::TYPE] =
        \Serfhos\MyConfigurableRoutes\Routing\PluginConfigurableByPageEnhancer::class;
}, 'my_configurable_routes');
