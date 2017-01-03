/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
define(
    [
        'jquery',
        'Magento_Checkout/js/model/url-builder'
    ],
    function ($, urlBuilder) {
        'use strict';

        return $.extend(
            urlBuilder,
            {
                storeCode: window.giftOptionsConfig.storeCode
            }
        );
    }
);
