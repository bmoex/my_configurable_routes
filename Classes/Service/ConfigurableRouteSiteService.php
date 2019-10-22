<?php

namespace Serfhos\MyConfigurableRoutes\Service;

use Serfhos\MyConfigurableRoutes\Domain\DataTransferObject\ConfigurableRouteEnhancer;
use Serfhos\MyConfigurableRoutes\Routing\PluginConfigurableByPageEnhancer;
use TYPO3\CMS\Core\Site\Entity\Site;

class ConfigurableRouteSiteService
{
    /**
     * @param  \TYPO3\CMS\Core\Site\Entity\Site  $site
     * @return \Serfhos\MyConfigurableRoutes\Domain\DataTransferObject\ConfigurableRouteEnhancer[]
     */
    public function getAllRouteEnhancers(Site $site): array
    {
        $pluginConfigurableByPageEnhancers = [];
        foreach ($site->getConfiguration()['routeEnhancers'] ?? [] as $enhancer) {
            if ($enhancer['type'] === PluginConfigurableByPageEnhancer::TYPE) {
                $pluginConfigurableByPageEnhancers[] = new ConfigurableRouteEnhancer($enhancer);
            }
        }

        return $pluginConfigurableByPageEnhancers;
    }
}
