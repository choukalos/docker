/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_Ui/js/form/element/abstract'
], function (Abstract) {
    'use strict';

    return Abstract.extend({

        /**
         * Disable required validation, when 'use config option' checked
         */
        handleRequired: function (newValue) {
            this.validation['required-entry'] = !newValue;
            this.required(!newValue);
            this.error(false);
        }
    });
});
