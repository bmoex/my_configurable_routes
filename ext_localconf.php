<?php

use Serfhos\MyConfigurableRoutes\Routing\ExtbaseConfigurableByPageEnhancer;
use Serfhos\MyConfigurableRoutes\Routing\PluginConfigurableByPageEnhancer;

call_user_func(function (): void {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['enhancers'][PluginConfigurableByPageEnhancer::TYPE] =
        PluginConfigurableByPageEnhancer::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['enhancers'][ExtbaseConfigurableByPageEnhancer::TYPE] =
        ExtbaseConfigurableByPageEnhancer::class;
});
