/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'uiComponent',
    'underscore',
    'Magento_Customer/js/customer-data',
    'jquery/jquery-storageapi'
], function ($, Component, _, customerData) {
    'use strict';

    return Component.extend({
        defaults: {
            cookieMessages: [],
            messages: []
        },

        /** @inheritdoc */
        initialize: function () {
            this._super();

            this.cookieMessages = $.cookieStorage.get('mage-messages');
            this.messages = customerData.get('messages').extend({
                disposableCustomerData: 'messages'
            });

            if (!_.isEmpty(this.messages().messages)) {
                customerData.set('messages', {});
            }

            $.cookieStorage.set('mage-messages', '');
        }
    });
});
