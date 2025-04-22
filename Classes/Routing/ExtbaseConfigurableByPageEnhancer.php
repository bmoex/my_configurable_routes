<?php

namespace Serfhos\MyConfigurableRoutes\Routing;

use Serfhos\MyConfigurableRoutes\Domain\DataTransferObject\ConfigurableRouteEnhancer;
use Serfhos\MyConfigurableRoutes\Exception\InvalidConfigurationException;
use Serfhos\MyConfigurableRoutes\Service\ConfigurableRouteSiteService;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Routing\Route;
use TYPO3\CMS\Core\Routing\RouteCollection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Routing\ExtbasePluginEnhancer;

/**
 * Used for extbase plugins like EXT:news.
 *
 * @see https://docs.typo3.org/permalink/t3coreapi:routing-extbase-plugin-enhancer
 *
 * This is usually used for arguments that are built with a `tx_myplugin_pi1` as namespace in GET / POST parameter.
 *
 * routeEnhancers:
 *   NewsPlugin:
 *     type: ExtbaseConfigurableByPage
 *     configurable:
 *       # Unique key
 *       key: news
 *       # Displayed label in page options
 *       label: Courses
 *     limitToPages: [13]
 *     extension: News
 *     plugin: Pi1
 *     routes:
 *       - routePath: '/list/'
 *         _controller: 'News::list'
 *       - routePath: '/list/{page}'
 *         _controller: 'News::list'
 *         _arguments:
 *           page: '@widget_0/currentPage'
 *       - routePath: '/detail/{news_title}'
 *         _controller: 'News::detail'
 *         _arguments:
 *           news_title: 'news'
 *       - routePath: '/tag/{tag_name}'
 *         _controller: 'News::list'
 *         _arguments:
 *           tag_name: 'overwriteDemand/tags'
 *       - routePath: '/list/{year}/{month}'
 *         _controller: 'News::list'
 *         _arguments:
 *           year: 'overwriteDemand/year'
 *           month: 'overwriteDemand/month'
 *         requirements:
 *           year: '\d+'
 *           month: '\d+'
 *     defaultController: 'News::list'
 *     defaults:
 *       page: '0'
 *     aspects:
 *       news_title:
 *         type: PersistedAliasMapper
 *         tableName: tx_news_domain_model_news
 *         routeFieldName: path_segment
 *       page:
 *         type: StaticRangeMapper
 *         start: '1'
 *         end: '100'
 *       month:
 *         type: StaticRangeMapper
 *         start: '1'
 *         end: '12'
 *       year:
 *         type: StaticRangeMapper
 *         start: '1984'
 *         end: '2525'
 *       tag_name:
 *         type: PersistedAliasMapper
 *         tableName: tx_news_domain_model_tag
 *         routeFieldName: slug
 */
class ExtbaseConfigurableByPageEnhancer extends ExtbasePluginEnhancer
{
    public const TYPE = 'ExtbaseConfigurableByPage';

    protected ConfigurableRouteEnhancer $enhancer;

    public function __construct(array $configuration)
    {
        parent::__construct($configuration);

        try {
            $this->enhancer = new ConfigurableRouteEnhancer($configuration);
        } catch (InvalidConfigurationException $e) {
            // Bypass exception when enhancer is not configured correctly
        }
    }

    /**
     * Extends route collection with all routes. Used during URL resolving.
     */
    public function enhanceForMatching(RouteCollection $collection): void
    {
        /** @var \TYPO3\CMS\Core\Routing\Route $defaultRoute */
        $defaultRoute = $collection->get('default');
        if ($this->isConfiguredForPage($defaultRoute)) {
            parent::enhanceForMatching($collection);
        }
    }

    /**
     * Extends route collection with routes that are relevant for given
     * parameters. Used during URL generation.
     */
    public function enhanceForGeneration(RouteCollection $collection, array $originalParameters): void
    {
        /** @var \TYPO3\CMS\Core\Routing\Route $defaultRoute */
        $defaultRoute = $collection->get('default');
        if ($this->isConfiguredForPage($defaultRoute)) {
            parent::enhanceForGeneration($collection, $originalParameters);
        }
    }

    protected function isConfiguredForPage(Route $route): bool
    {
        return GeneralUtility::makeInstance(ConfigurableRouteSiteService::class)
            ->enhancerIsEnabledByRoute($this->enhancer, $route);
    }
}
