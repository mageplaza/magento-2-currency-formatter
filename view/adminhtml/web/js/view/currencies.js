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
    'rjsResolver'
], function($, ko, _, Component, resolver) {
    'use strict';
    
    var disableFields = [
        '#mpcurrencyformatter_show_symbol_',
        '#mpcurrencyformatter_group_separator_',
        '#mpcurrencyformatter_decimal_number_',
        '#mpcurrencyformatter_minus_sign_',
        '#mpcurrencyformatter_symbol_',
        '#mpcurrencyformatter_decimal_separator_',
        '#mpcurrencyformatter_show_minus_'
    ];
    
    return Component.extend({
        defaults: {
            template: 'Mageplaza_CurrencyFormatter/currencies'
        },
        previewDemo : ko.observable('preview Demo'),
        currencyConfig : $('#row_mpcurrencyformatter_general_currencies td.value'),
        useDefaultClass : $('#row_mpcurrencyformatter_general_currencies td.use-default'),
        
        initialize: function () {
            this._super();
            
            this.currencyConfig.attr('colspan', '2');
            if (this.useDefaultClass) {
                this.useDefaultClass.remove();
            }
            
            resolver(this.afterResolveDocument.bind(this));
        },
        
        afterResolveDocument: function () {
            var self = this;
            
            _.each(this.mpCurrencies, function(currency) {
                var useDefault = parseInt(currency.config.use_default, 10);
                
                self.toggleUseDefault(currency.code, useDefault);
                self.createPreview(currency, self);
            });
        },
        
        createPreview: function (currency, parent) {
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
                symbol += newConfig.minus_sign;
            }
            
            parent.finalizePreview(currency.code, firstResult + decimal, newConfig, symbol);
        },
        
        finalizePreview: function (code, secondResult, config, symbol) {
            var thirdResult = null;
            
            if (config.show_minus === 'before_value') {
                secondResult = config.minus_sign + secondResult;
            }
            
            if (config.show_minus === 'after_value') {
                secondResult += config.minus_sign;
            }
            
            switch (config.show_symbol) {
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
                    if (config.show_minus === 'after_symbol' || config.show_minus === 'before_symbol') {
                        thirdResult = config.minus_sign + secondResult;
                    }
                    break;
            }
            
            $('#demo_'+ code).text(thirdResult);
        },
        
        useDefault: function (currency, parent) {
            if (parseInt(currency.config.use_default, 10)) {
                currency.config.use_default = 0;
            }else {
                currency.config.use_default = 1;
            }
            
            parent.toggleUseDefault(currency.code, currency.config.use_default);
        },
        
        toggleUseDefault: function (code, useDefault) {
            if (useDefault) {
                _.each(disableFields, function(field) {
                    $(field + code).attr('disabled', 'disabled');
                });
            }else {
                _.each(disableFields, function(field) {
                    $(field + code).removeAttr('disabled');
                });
            }
        }
    });
});