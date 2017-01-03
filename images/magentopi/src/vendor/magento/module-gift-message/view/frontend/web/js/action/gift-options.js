/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define*/
define(
    [
        'Magento_GiftMessage/js/model/url-builder',
        'mage/storage',
        'Magento_Ui/js/model/messageList',
        'Magento_Checkout/js/model/error-processor',
        'mage/url',
        'Magento_Checkout/js/model/quote'
    ],
    function (urlBuilder, storage, messageList, errorProcessor, url, quote) {
        'use strict';

        return function (giftMessage, remove) {
            var serviceUrl;

            url.setBaseUrl(giftMessage.getConfigValue('baseUrl'));

            if (giftMessage.getConfigValue('isCustomerLoggedIn')) {
                serviceUrl = urlBuilder.createUrl('/carts/mine/gift-message', {});

                if (giftMessage.itemId != 'orderLevel') {
                    serviceUrl = urlBuilder.createUrl('/carts/mine/gift-message/:itemId', {
                        itemId: giftMessage.itemId
                    });
                }
            } else {
                serviceUrl = urlBuilder.createUrl('/guest-carts/:cartId/gift-message', {
                    cartId: quote.getQuoteId()
                });

                if (giftMessage.itemId != 'orderLevel') {
                    serviceUrl = urlBuilder.createUrl(
                        '/guest-carts/:cartId/gift-message/:itemId',
                        {
                            cartId: quote.getQuoteId(), itemId: giftMessage.itemId
                        }
                    );
                }
            }
            messageList.clear();

            storage.post(
                serviceUrl,
                JSON.stringify({
                    gift_message: giftMessage.getSubmitParams(remove)
                })
            ).done(
                function (response) {
                    giftMessage.reset();
                    _.each(giftMessage.getAfterSubmitCallbacks(), function (callback) {
                        if (_.isFunction(callback)) {
                            callback();
                        }
                    });
                }
            ).fail(
                function (response) {
                    errorProcessor.process(response);
                }
            );
        };
    }
);
