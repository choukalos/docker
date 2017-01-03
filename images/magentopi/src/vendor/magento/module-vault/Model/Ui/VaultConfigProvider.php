<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Vault\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Vault\Api\PaymentMethodListInterface;

class VaultConfigProvider implements ConfigProviderInterface
{
    const IS_ACTIVE_CODE = 'is_active_payment_token_enabler';

    /**
     * @var string
     */
    private static $vaultCode = 'vault';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var SessionManagerInterface
     */
    private $session;

    /**
     * @var PaymentMethodListInterface
     */
    private $vaultPaymentList;

    /**
     * VaultConfigProvider constructor.
     * @param StoreManagerInterface $storeManager
     * @param SessionManagerInterface $session
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        SessionManagerInterface $session
    ) {
        $this->storeManager = $storeManager;
        $this->session = $session;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        $availableMethods = [];
        $storeId = $this->storeManager->getStore()->getId();
        $vaultPayments = $this->getVaultPaymentList()->getActiveList($storeId);
        $customerId = $this->session->getCustomerId();

        foreach ($vaultPayments as $method) {
            $availableMethods[$method->getCode()] = [
                'is_enabled' => $customerId !== null && $method->isActive($storeId)
            ];
        }

        return [
            self::$vaultCode => $availableMethods
        ];
    }

    /**
     * Get vault payment list instance
     * @return PaymentMethodListInterface
     * @deprecated
     */
    private function getVaultPaymentList()
    {
        if ($this->vaultPaymentList === null) {
            $this->vaultPaymentList = ObjectManager::getInstance()->get(PaymentMethodListInterface::class);
        }
        return $this->vaultPaymentList;
    }
}
