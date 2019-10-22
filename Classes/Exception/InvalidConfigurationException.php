<?php

namespace Serfhos\MyConfigurableRoutes\Exception;

class InvalidConfigurationException extends \InvalidArgumentException
{
    /**
     * @return \Serfhos\MyConfigurableRoutes\Exception\InvalidConfigurationException
     */
    public static function invalidConfigurableRouteEnhancer(): InvalidConfigurationException
    {
        return new self(
            'PluginConfigurableByPage requires a key and label.',
            1571654309279
        );
    }
}
