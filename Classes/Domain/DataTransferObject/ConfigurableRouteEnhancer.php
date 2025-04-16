<?php

namespace Serfhos\MyConfigurableRoutes\Domain\DataTransferObject;

use Serfhos\MyConfigurableRoutes\Exception\InvalidConfigurationException;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * DTO: ConfigurableRouteEnhancer
 */
class ConfigurableRouteEnhancer
{
    public function __construct(protected array $row)
    {
        if (!isset($row['configurable']['key'], $row['configurable']['label'])) {
            throw InvalidConfigurationException::invalidConfigurableRouteEnhancer();
        }
    }

    public function getLabel(): string
    {
        $label = $this->row['configurable']['label'] ?? '';
        if (str_starts_with($label, 'LLL:')) {
            $label = LocalizationUtility::translate($label);
        }

        return $label ?? '';
    }

    public function getKey(): string
    {
        return $this->row['configurable']['key'] ?? md5(json_encode($this->row));
    }

    public function getIcon(): string
    {
        return $this->row['configurable']['icon'] ?? '';
    }
}
