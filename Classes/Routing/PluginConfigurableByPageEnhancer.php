<?php

namespace Serfhos\MyConfigurableRoutes\Routing;

use Serfhos\MyConfigurableRoutes\Domain\DataTransferObject\ConfigurableRouteEnhancer;
use Serfhos\MyConfigurableRoutes\Exception\InvalidConfigurationException;
use TYPO3\CMS\Core\Routing\Enhancer\PluginEnhancer;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Routing\Route;
use TYPO3\CMS\Core\Routing\RouteCollection;

/**
 * Used for plugins like EXT:news.
 *
 * This is usually used for arguments that are built with a `tx_myplugin_pi1` as namespace in GET / POST parameter.
 *
 * routeEnhancers:
 *   NewsDetailPlugin:
 *     type: PluginConfigurableByPage
 *     configurable:
 *       # Unique key
 *       key: news
 *       # Displayed label in page options
 *       label: Courses
 *     routePath: '/{article}'
 *     namespace: tx_news_detail
 *     _arguments:
 *       article: news
 *     requirements:
 *       article: '.*'
 */
class PluginConfigurableByPageEnhancer extends PluginEnhancer
{
    public const TYPE = 'PluginConfigurableByPage';

    /** @var \Serfhos\MyConfigurableRoutes\Domain\DataTransferObject\ConfigurableRouteEnhancer */
    protected $enhancer;

    /**
     * @param  array  $configuration
     */
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
     * @param  \TYPO3\CMS\Core\Routing\Route  $route
     * @param  array  $results
     * @param  array  $remainingQueryParameters
     * @return \TYPO3\CMS\Core\Routing\PageArguments
     */
    public function buildResult(Route $route, array $results, array $remainingQueryParameters = []): PageArguments
    {
        if ($this->isConfiguredForPage($route)) {
            return parent::buildResult($route, $results, $remainingQueryParameters);
        }

        $page = $route->getOption('_page');
        $pageId = (int)($page['l10n_parent'] > 0 ? $page['l10n_parent'] : $page['uid']);
        $type = $this->resolveType($route, $remainingQueryParameters);

        return new PageArguments($pageId, $type, $results, [], $remainingQueryParameters);
    }

    /**
     * Extends route collection with all routes. Used during URL resolving.
     *
     * @param  \TYPO3\CMS\Core\Routing\RouteCollection  $collection
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
     *
     * @param  \TYPO3\CMS\Core\Routing\RouteCollection  $collection
     * @param  array  $parameters
     */
    public function enhanceForGeneration(RouteCollection $collection, array $parameters): void
    {
        /** @var \TYPO3\CMS\Core\Routing\Route $defaultRoute */
        $defaultRoute = $collection->get('default');
        if ($this->isConfiguredForPage($defaultRoute)) {
            parent::enhanceForGeneration($collection, $parameters);
        }
    }

    /**
     * @param  \TYPO3\CMS\Core\Routing\Route  $route
     * @return bool
     */
    protected function isConfiguredForPage(Route $route): bool
    {
        if ($this->enhancer === null) {
            return false;
        }

        try {
            $page = $route->getOption('_page') ?? [];

            return $page['my_configurable_routes_type'] === $this->enhancer->getKey();
        } catch (\Exception $e) {
            return false;
        }
    }
}
