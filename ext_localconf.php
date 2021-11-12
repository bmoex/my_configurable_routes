<?php

call_user_func(function (): void {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['routing']['enhancers'][\Serfhos\MyConfigurableRoutes\Routing\PluginConfigurableByPageEnhancer::TYPE] =
        \Serfhos\MyConfigurableRoutes\Routing\PluginConfigurableByPageEnhancer::class;
});
