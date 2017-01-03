<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Vault\Setup;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Model\CreditCardTokenFactory;

/**
 * Class UpgradeData
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * @inheritdoc
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        // data update for Vault module < 2.0.1
        if (version_compare($context->getVersion(), '2.0.1', '<')) {
            // update sets credit card as default token type
            $setup->getConnection()->update($setup->getTable(InstallSchema::PAYMENT_TOKEN_TABLE), [
                PaymentTokenInterface::TYPE => CreditCardTokenFactory::TOKEN_TYPE_CREDIT_CARD
            ], PaymentTokenInterface::TYPE . ' = ""');
        }

        // data update for Vault module < 2.0.2
        if (version_compare($context->getVersion(), '2.0.2', '<')) {
            // update converts additional info with token metadata to single dimensional array
            $salesConnection = $setup->getConnection('sales');
            $select = $salesConnection->select()
                ->from($setup->getTable('sales_order_payment'), 'entity_id')
                ->columns(['additional_information'])
                ->where('additional_information LIKE ?', '%token_metadata%');

            $items = $salesConnection->fetchAll($select);
            foreach ($items as $item) {
                $additionalInfo = unserialize($item['additional_information']);
                $additionalInfo[PaymentTokenInterface::CUSTOMER_ID] =
                    $additionalInfo['token_metadata'][PaymentTokenInterface::CUSTOMER_ID];
                $additionalInfo[PaymentTokenInterface::PUBLIC_HASH] =
                    $additionalInfo['token_metadata'][PaymentTokenInterface::PUBLIC_HASH];
                unset($additionalInfo['token_metadata']);

                $salesConnection->update(
                    $setup->getTable('sales_order_payment'),
                    ['additional_information' => serialize($additionalInfo)],
                    ['entity_id = ?' => $item['entity_id']]
                );
            }
        }

        $setup->endSetup();
    }

}
