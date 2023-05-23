<?php

namespace Serfhos\MyConfigurableRoutes\Service;

use Serfhos\MyConfigurableRoutes\Domain\DataTransferObject\ConfigurableRouteEnhancer;
use Serfhos\MyConfigurableRoutes\Routing\PluginConfigurableByPageEnhancer;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Routing\Route;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ConfigurableRouteSiteService implements SingletonInterface
{
    protected array $pageTypes = [];

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

    public function enhancerIsEnabledByRoute(ConfigurableRouteEnhancer $enhancer, Route $route): bool
    {
        try {
            $page = $route->getOption('_page') ?? [];
            $type = $this->getTypeForPage($page);

            return $type === $enhancer->getKey();
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getTypeForPage(array $page): ?string
    {
        $identifier = $page['uid'] ?? 0;
        if (!isset($this->pageTypes[$identifier])) {
            if (isset($page['my_configurable_routes_type'])) {
                $this->pageTypes[$identifier] = $page['my_configurable_routes_type'];
            } else {
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getQueryBuilderForTable('pages');

                $queryBuilder
                    ->getRestrictions()
                    ->removeAll();

                $statement = $queryBuilder
                    ->select('my_configurable_routes_type')
                    ->from('pages')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($identifier, \PDO::PARAM_INT)
                        )
                    )
                    ->executeQuery();

                $this->pageTypes[$identifier] = $statement->fetchOne();
            }
        }

        return $this->pageTypes[$identifier];
    }
}
