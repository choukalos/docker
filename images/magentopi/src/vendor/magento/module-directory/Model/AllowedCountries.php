<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Directory\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class CountryHandler
 */
class AllowedCountries
{
    const ALLOWED_COUNTRIES_PATH = 'general/country/allow';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @return void
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Retrieve all allowed countries for scope or scopes
     *
     * @param string | null $scopeCode
     * @param string $scope
     * @return array
     */
    public function getAllowedCountries(
        $scope = ScopeInterface::SCOPE_WEBSITE,
        $scopeCode = null
    ) {
        if (empty($scopeCode)) {
            $scopeCode = $this->getDefaultScopeCode($scope);
        }

        switch ($scope) {
            case ScopeInterface::SCOPE_WEBSITES:
            case ScopeInterface::SCOPE_STORES:
                $allowedCountries = [];
                foreach ($scopeCode as $singleFilter) {
                    $allowedCountries = array_merge(
                        $allowedCountries,
                        $this->getCountriesFromConfig($this->getSingleScope($scope), $singleFilter)
                    );
                }
                break;
            default:
                $allowedCountries = $this->getCountriesFromConfig($scope, $scopeCode);
        }

        return $this->makeCountriesUnique($allowedCountries);
    }

    /**
     * Resolve scope code by scope
     *
     * @throws \InvalidArgumentException
     * @param string $scope
     * @return array|int
     */
    private function getDefaultScopeCode($scope)
    {
        switch ($scope) {
            case ScopeInterface::SCOPE_WEBSITE:
                return $this->storeManager->getWebsite()->getId();
            case ScopeInterface::SCOPE_STORE:
                return $this->storeManager->getStore()->getId();
            case ScopeInterface::SCOPE_GROUP:
                return $this->storeManager->getGroup()->getId();
            case ScopeInterface::SCOPE_WEBSITES:
                return [$this->storeManager->getWebsite()->getId()];
            case ScopeInterface::SCOPE_STORES:
                return [$this->storeManager->getStore()->getId()];
            default:
                throw new \InvalidArgumentException("Invalid scope is specified");
        }
    }

    /**
     * Return Unique Countries by merging them by keys
     *
     * @param array $allowedCountries
     * @return array
     */
    public function makeCountriesUnique(array $allowedCountries)
    {
        return array_combine($allowedCountries, $allowedCountries);
    }

    /**
     * Takes countries from Countries Config data
     *
     * @param string $scope
     * @param int $scopeCode
     * @return array
     */
    public function getCountriesFromConfig($scope, $scopeCode)
    {
        return explode(
            ',',
            (string) $this->scopeConfig->getValue(
                self::ALLOWED_COUNTRIES_PATH,
                $scope,
                $scopeCode
            )
        );
    }

    /**
     * Return Single Scope
     *
     * @param string $scope
     * @return string
     */
    private function getSingleScope($scope)
    {
        if ($scope == ScopeInterface::SCOPE_WEBSITES) {
            return ScopeInterface::SCOPE_WEBSITE;
        }

        if ($scope == ScopeInterface::SCOPE_STORES) {
            return ScopeInterface::SCOPE_STORE;
        }

        return $scope;
    }
}
