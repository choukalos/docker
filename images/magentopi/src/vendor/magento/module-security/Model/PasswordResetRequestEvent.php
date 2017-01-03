<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Security\Model;

/**
 * PasswordResetRequestEvent Model
 *
 * @method string getAccountReference()
 * @method PasswordResetRequestEvent setAccountReference(string $reference)
 * @method int getRequestType()
 * @method string getCreatedAt()
 * @method PasswordResetRequestEvent setRequestType(int $requestType)
 * @method string getIp()
 * @method PasswordResetRequestEvent setIp(int $ip)
 */
class PasswordResetRequestEvent extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Type of the event under a security control definition
     */

    /**
     * Customer request a password reset
     */
    const CUSTOMER_PASSWORD_RESET_REQUEST = 0;

    /**
     * Admin User request a password reset
     */
    const ADMIN_PASSWORD_RESET_REQUEST = 1;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magento\Security\Model\ResourceModel\PasswordResetRequestEvent');
    }
}
