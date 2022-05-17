/*
 * *
 *  * Magepow
 *  * @category    Magepow
 *  * @copyright   Copyright (c) 2014 Magepow (http://www.magepow.com/)
 *  * @license     http://www.magepow.com/license-agreement.html
 *  * @Author: DavidDuong
 *  * @@Create Date: 4/16/19 3:20 PM
 *  * @@Modify Date: 7/14/20 3:20 PM
 *
 *
 */

define([
    'jquery',
    'Magento_Catalog/js/price-utils',
    'Magepow_Layerednav/js/layerednav',
    'jquery/ui-modules/widgets/slider',
    'jquery/ui'
], function($, ultil) {
    "use strict";

    $.widget('magepow.LayerednavSlider', $.magepow.layerednav, {
        options: {
            sliderElement: '#layerednav_price_slider',
            textElement: '#layered_ajax_price_text'
        },
        _create: function () {
            var self = this;
            $(this.options.sliderElement).slider({
                range: true,
                min: self.options.minValue,
                max: self.options.maxValue,
                step: self.options.step,
                values: [self.options.selectedFrom, self.options.selectedTo],
                slide: function( event, ui ) {
                    self.displayText(ui.values[0], ui.values[1]);
                },
                change: function(event, ui) {
                    self.ajaxSubmit(self.getUrl(ui.values[0], ui.values[1]));
                }
            });
            this.displayText(this.options.selectedFrom, this.options.selectedTo);
        },

        getUrl: function(from, to){
            return this.options.ajaxUrl.replace(encodeURI('{price_start}'), from).replace(encodeURI('{price_end}'), to);
        },

        displayText: function(from, to){
            $(this.options.textElement).html('<span class="from_fixed">'+this.formatPrice(from) + '</span><span class="space_fixed"> - </span><span class="to_fixed">' + this.formatPrice(to)+'</span>');
        },

        formatPrice: function(value) {
            return ultil.formatPrice(value, this.options.priceFormat);
        }
    });

    return $.magepow.LayerednavSlider;
});
