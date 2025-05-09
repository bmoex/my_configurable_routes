<?php

namespace Serfhos\MyConfigurableRoutes\Routing;

use Serfhos\MyConfigurableRoutes\Domain\DataTransferObject\ConfigurableRouteEnhancer;
use Serfhos\MyConfigurableRoutes\Exception\InvalidConfigurationException;
use Serfhos\MyConfigurableRoutes\Service\ConfigurableRouteSiteService;
use TYPO3\CMS\Core\Routing\Enhancer\PluginEnhancer;
use TYPO3\CMS\Core\Routing\Route;
use TYPO3\CMS\Core\Routing\RouteCollection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
    public function enhanceForGeneration(RouteCollection $collection, array $parameters): void
    {
        /** @var \TYPO3\CMS\Core\Routing\Route $defaultRoute */
        $defaultRoute = $collection->get('default');
        if ($this->isConfiguredForPage($defaultRoute)) {
            parent::enhanceForGeneration($collection, $parameters);
        }
    }

    protected function isConfiguredForPage(Route $route): bool
    {
        return GeneralUtility::makeInstance(ConfigurableRouteSiteService::class)
            ->enhancerIsEnabledByRoute($this->enhancer, $route);
    }
}
