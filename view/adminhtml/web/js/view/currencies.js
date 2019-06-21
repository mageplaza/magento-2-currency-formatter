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
            
            this.currencyConfig.attr('colspan', '2');
            $('.mpcf-use-default').each(function() {
                var currencyCode = $(this).data('currency');
                
                this.toggleDisable(currencyCode, parseInt(this.value, 10));
                $(this).on('change', function() {
                    self.toggleDisable(currencyCode, parseInt(this.value, 10));
                });
            });
            
            if (this.useDefaultClass) {
                this.useDefaultClass.remove();
            }
            
            console.log(this);
            
            this._super();
        },
        
        toggleDisable: function(currencyCode, value) {
            $.each(this.disableFields, function(key, field) {
                if(value) {
                    $(field + currencyCode).attr('disabled', 'disabled');
                }else {
                    $(field + currencyCode).removeAttr('disabled');
                }
            });
        },
        
    });
});