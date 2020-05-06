<?php

namespace Serfhos\MyConfigurableRoutes\Domain\DataTransferObject;

use Serfhos\MyConfigurableRoutes\Exception\InvalidConfigurationException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * DTO: ConfigurableRouteEnhancer
 */
class ConfigurableRouteEnhancer
{
    /** @var array */
    protected $row;

    /**
     * @param  array  $row
     */
    public function __construct(array $row)
    {
        if (!isset($row['configurable']['key'], $row['configurable']['label'])) {
            throw InvalidConfigurationException::invalidConfigurableRouteEnhancer();
        }
        $this->row = $row;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        $label = $this->row['configurable']['label'] ?? '';
        if (GeneralUtility::isFirstPartOfStr($label, 'LLL:')) {
            $label = LocalizationUtility::translate($label);
        }

        return $label ?? '';
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->row['configurable']['key'] ?? md5(json_encode($this->row));
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->row['configurable']['icon'] ?? '';
    }
}
