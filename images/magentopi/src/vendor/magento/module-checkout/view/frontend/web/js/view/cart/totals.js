/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/model/shipping-service'
    ],
    function (Component, totalsService, shippingService) {
        'use strict';

        return Component.extend({

            isLoading: totalsService.isLoading,

            /**
             * @override
             */
            initialize: function () {
                this._super();
                totalsService.totals.subscribe(function () {
                    window.dispatchEvent(new Event('resize'));
                });
                shippingService.getShippingRates().subscribe(function () {
                    window.dispatchEvent(new Event('resize'));
                });
            }
        });
    }
);
