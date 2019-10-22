<?php

namespace Serfhos\MyConfigurableRoutes\Service;

use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * TCA: itemsProcFunc option items
 */
class TableConfigurationService
{
    /**
     * Add configurable route enhancers to $parameters[&items]
     *
     * @param  array  $parameters
     * @return array
     * @used-by EXT:my_configurable_routes/Configuration/TCA/Overrides/pages.php -> my_configurable_routes_type
     */
    public function addPluginRouteOptions(array $parameters): array
    {
        $items = &$parameters['items'] ?? [];

        $site = $this->getSiteForRow($parameters['row']);
        if ($site) {
            foreach ($this->getConfigurableRouteSiteService()->getAllRouteEnhancers($site) as $enhancer) {
                $items[] = [
                    $enhancer->getLabel(),
                    $enhancer->getKey(),
                    $enhancer->getIcon(),
                ];
            }
        }

        return $items;
    }

    /**
     * @param  array  $row
     * @return \TYPO3\CMS\Core\Site\Entity\Site|null
     */
    protected function getSiteForRow(array $row): ?Site
    {
        $site = null;
        try {
            $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
            if ($row['pid'] === 0) {
                $site = $siteFinder->getSiteByRootPageId($row['uid']);
            } elseif ($row['uid'] > 0) {
                $site = $siteFinder->getSiteByPageId($row['uid']);
            }
        } catch (SiteNotFoundException $e) {
            // Never throw site not found exception
        }

        return $site;
    }

    /**
     * @return \Serfhos\MyConfigurableRoutes\Service\ConfigurableRouteSiteService
     */
    protected function getConfigurableRouteSiteService(): ConfigurableRouteSiteService
    {
        return GeneralUtility::makeInstance(ConfigurableRouteSiteService::class);
    }
}
