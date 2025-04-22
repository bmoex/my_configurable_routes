<?php

namespace Serfhos\MyConfigurableRoutes\Exception;

class InvalidConfigurationException extends \InvalidArgumentException
{
    public static function invalidConfigurableRouteEnhancer(): InvalidConfigurationException
    {
        return new self(
            'Configuration requires a key and label in configurable.',
            1571654309279
        );
    }
}
