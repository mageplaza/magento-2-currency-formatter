/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_CurrencyFormatter
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */
define([
    'jquery',
    'ko',
    'underscore',
    'uiComponent',
], function($, ko, _, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Mageplaza_CurrencyFormatter/currencies'
        },
        previewDemo : ko.observable('preview Demo'),
        currencyConfig : $('#row_mpcurrencyformatter_general_currencies td.value'),
        useDefaultClass : $('#row_mpcurrencyformatter_general_currencies td.use-default'),
        disableFields : [
            '#mpcurrencyformatter_show_symbol_',
            '#mpcurrencyformatter_group_separator_',
            '#mpcurrencyformatter_decimal_number_',
            '#mpcurrencyformatter_minus_sign_',
            '#mpcurrencyformatter_symbol_',
            '#mpcurrencyformatter_decimal_separator_',
            '#mpcurrencyformatter_show_minus_sign_',
        ],

        initialize: function () {
            var self = this;

            this._super();

            this.currencyConfig.attr('colspan', '2');
            if (this.useDefaultClass) {
                this.useDefaultClass.remove();
            }
            this.createPreview();
        },

        useDefault: function () {
            console.log(this);
        },

        createPreview: function (currency) {
            var newConfig = currency.config,
                firstResult = '49' + newConfig.group_separator + '456',
                decimalPart = '0000',
                decimal = '',
                symbol = newConfig.symbol;

            if (newConfig.decimal_number > 0) {
                decimal = newConfig.decimal_separator + decimalPart.substr(0, newConfig.decimal_number);
            }

            if (newConfig.show_minus === 'before_symbol') {
                symbol = newConfig.minus_sign + symbol;
            }

            if (newConfig.show_minus === 'after_symbol') {
                symbol = symbol + newConfig.minus_sign;
            }

            var secondResult = firstResult + decimal,
                thirdResult = null;

            if (newConfig.show_minus === 'before_value') {
                secondResult = newConfig.minus_sign + secondResult;
            }

            if (newConfig.show_minus === 'after_value') {
                secondResult = secondResult + newConfig.minus_sign;
            }

            switch (newConfig.show_symbol) {
                case 'before':
                    thirdResult = symbol + secondResult;
                    break;
                case 'before_with_space':
                    thirdResult = symbol + ' ' + secondResult;
                    break;
                case 'after':
                    thirdResult = secondResult + symbol;
                    break;
                case 'after_with_space':
                    thirdResult = secondResult + ' ' + symbol;
                    break;
                default:
                    thirdResult = secondResult;
                    break;
            }

            self.previewDemo(thirdResult);
        },

    });
});