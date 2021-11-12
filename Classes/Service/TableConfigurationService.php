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
    /** @var \TYPO3\CMS\Core\Site\SiteFinder */
    protected $siteFinder;

    /** @var \Serfhos\MyConfigurableRoutes\Service\ConfigurableRouteSiteService */
    protected $configurableRouteSiteService;

    /**
     * @param  \Serfhos\MyConfigurableRoutes\Service\ConfigurableRouteSiteService  $configurableRouteSiteService
     */
    public function __construct(SiteFinder $siteFinder, ConfigurableRouteSiteService $configurableRouteSiteService)
    {
        $this->siteFinder = $siteFinder;
        $this->configurableRouteSiteService = $configurableRouteSiteService;
    }

    /**
     * Add configurable route enhancers to $parameters[&items]
     *
     * @param  array  $parameters
     * @return void
     * @used-by EXT:my_configurable_routes/Configuration/TCA/Overrides/pages.php -> my_configurable_routes_type
     */
    public function addPluginRouteOptions(array $parameters): void
    {
        $site = $this->getSiteForRow($parameters['row']);
        if ($site) {
            foreach ($this->configurableRouteSiteService->getAllRouteEnhancers($site) as $enhancer) {
                $parameters['items'][] = [
                    $enhancer->getLabel(),
                    $enhancer->getKey(),
                    $enhancer->getIcon(),
                ];
            }
        }
    }

    /**
     * @param  array  $row
     * @return \TYPO3\CMS\Core\Site\Entity\Site|null
     */
    protected function getSiteForRow(array $row): ?Site
    {
        try {
            return $this->siteFinder->getSiteByPageId($row['uid']);
        } catch (SiteNotFoundException $e) {
            // Never throw site not found exception
        }

        return null;
    }

    /**
     * @return \Serfhos\MyConfigurableRoutes\Service\ConfigurableRouteSiteService
     */
    protected function getConfigurableRouteSiteService(): ConfigurableRouteSiteService
    {
        return GeneralUtility::makeInstance(ConfigurableRouteSiteService::class);
    }
}
