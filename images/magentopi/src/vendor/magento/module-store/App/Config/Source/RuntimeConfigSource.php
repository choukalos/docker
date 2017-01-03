<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Store\App\Config\Source;

use Magento\Framework\App\Config\ConfigSourceInterface;
use Magento\Framework\App\DeploymentConfig;
use Magento\Store\Model\Group;
use Magento\Store\Model\ResourceModel\Website\CollectionFactory as WebsiteCollectionFactory;
use Magento\Store\Model\ResourceModel\Group\CollectionFactory as GroupCollectionFactory;
use Magento\Store\Model\ResourceModel\Store\CollectionFactory as StoreCollectionFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\Website;
use Magento\Store\Model\WebsiteFactory;
use Magento\Store\Model\GroupFactory;
use Magento\Store\Model\StoreFactory;

/**
 * Class RuntimeConfigSource
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RuntimeConfigSource implements ConfigSourceInterface
{
    /**
     * @var WebsiteCollectionFactory
     */
    private $websiteCollectionFactory;

    /**
     * @var GroupCollectionFactory
     */
    private $groupCollectionFactory;

    /**
     * @var StoreCollectionFactory
     */
    private $storeCollectionFactory;

    /**
     * @var DeploymentConfig
     */
    private $deploymentConfig;

    /**
     * @var WebsiteFactory
     */
    private $websiteFactory;

    /**
     * @var GroupFactory
     */
    private $groupFactory;

    /**
     * @var StoreFactory
     */
    private $storeFactory;

    /**
     * DynamicDataProvider constructor.
     *
     * @param WebsiteCollectionFactory $websiteCollectionFactory
     * @param GroupCollectionFactory $groupCollectionFactory
     * @param StoreCollectionFactory $storeCollectionFactory
     * @param WebsiteFactory $websiteFactory
     * @param GroupFactory $groupFactory
     * @param StoreFactory $storeFactory
     * @param DeploymentConfig $deploymentConfig
     */
    public function __construct(
        WebsiteCollectionFactory $websiteCollectionFactory,
        GroupCollectionFactory $groupCollectionFactory,
        StoreCollectionFactory $storeCollectionFactory,
        WebsiteFactory $websiteFactory,
        GroupFactory $groupFactory,
        StoreFactory $storeFactory,
        DeploymentConfig $deploymentConfig
    ) {
        $this->websiteCollectionFactory = $websiteCollectionFactory;
        $this->groupCollectionFactory = $groupCollectionFactory;
        $this->storeCollectionFactory = $storeCollectionFactory;
        $this->deploymentConfig = $deploymentConfig;
        $this->websiteFactory = $websiteFactory;
        $this->groupFactory = $groupFactory;
        $this->storeFactory = $storeFactory;
    }

    /**
     * @inheritdoc
     */
    public function get($path = '')
    {
        if (strpos($path, '/') === false) {
            $scopePool = $path;
            $scopeCode = null;
        } else {
            list($scopePool, $scopeCode) = explode('/', $path);
        }

        $data = [];
        if ($this->canUseDatabase()) {
            switch ($scopePool) {
                case 'websites':
                    $data['websites'] = $this->getWebsitesData($scopeCode);
                    break;
                case 'groups':
                    $data['groups'] = $this->getGroupsData($scopeCode);
                    break;
                case 'stores':
                    $data['stores'] = $this->getStoresData($scopeCode);
                    break;
                default:
                    $data = [
                        'websites' => $this->getWebsitesData(),
                        'groups' => $this->getGroupsData(),
                        'stores' => $this->getStoresData(),
                    ];
                    break;
            }
        }

        return $data;
    }

    /**
     * @param string|null $code
     * @return array
     */
    private function getWebsitesData($code = null)
    {
        if ($code !== null) {
            $website = $this->websiteFactory->create();
            $website->load($code);
            $data[$code] = $website->getData();
        } else {
            $collection = $this->websiteCollectionFactory->create();
            $collection->setLoadDefault(true);
            $data = [];
            /** @var Website $website */
            foreach ($collection as $website) {
                $data[$website->getCode()] = $website->getData();
            }
        }
        return $data;
    }

    /**
     * @param string|null $id
     * @return array
     */
    private function getGroupsData($id = null)
    {
        if ($id !== null) {
            $group = $this->groupFactory->create();
            $group->load($id);
            $data[$id] = $group->getData();
        } else {
            $collection = $this->groupCollectionFactory->create();
            $collection->setLoadDefault(true);
            $data = [];
            /** @var Group $group */
            foreach ($collection as $group) {
                $data[$group->getId()] = $group->getData();
            }
        }
        return $data;
    }

    /**
     * @param string|null $code
     * @return array
     */
    private function getStoresData($code = null)
    {
        if ($code !== null) {
            $store = $this->storeFactory->create();

            if (is_numeric($code)) {
                $store->load($code);
            } else {
                $store->load($code, 'code');
            }

            $data[$code] = $store->getData();
        } else {
            $collection = $this->storeCollectionFactory->create();
            $collection->setLoadDefault(true);
            $data = [];
            /** @var Store $store */
            foreach ($collection as $store) {
                $data[$store->getCode()] = $store->getData();
            }
            return $data;
        }
        return $data;
    }

    /**
     * Check whether db connection is available and can be used
     *
     * @return bool
     */
    private function canUseDatabase()
    {
        return $this->deploymentConfig->get('db');
    }
}
