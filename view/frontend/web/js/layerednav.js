/*
 * *
 *  * Magepow
 *  * @category    Magepow
 *  * @copyright   Copyright (c) 2014 Magepow (http://www.magepow.com/)
 *  * @license     http://www.magepow.com/license-agreement.html
 *  * @Author: DavidDuong
 *  * @@Create Date: 4/16/19 3:15 PM
 *  * @@Modify Date: 7/14/20 3:14 PM
 *
 *
 */
define([
    'jquery',
    'jquery/ui',
    'productListToolbarForm'
], function ($) {
    "use strict";

    var running = false;
    $.widget('magepow.layerednav', {

        options: {
            productsListSelector: '#layerednav-list-products',
            navigationSelector: '#layerednav-filter-block'
        },

        _create: function () {
            this.initObserve();
            this.initProductList();
            this.initClickShopBy();
            this.filterActive();
        },

        initObserve: function () {
            var self = this;
            var aElements = this.element.find('a');
            aElements.each(function (index) {
                var el = $(this);
                var link = self.ValidateUrl(el.prop('href'));
                if(!link) return;

                el.off('click').on('click', function (e) {
                    if (el.hasClass('swatch-option-link-layered')) {
                        var childEl = el.find('.swatch-option');
                        childEl.addClass('selected');
                    } else {
                        var checkboxEl = el.find('input[type=checkbox]');
                        checkboxEl.prop('checked', !checkboxEl.prop('checked'));
                    }

                    self.ajaxSubmit(link);
                    e.stopPropagation();
                    e.preventDefault();
                });

                var checkbox = el.find('input[type=checkbox]');
                checkbox.off('click').on('click', function (e) {
                    self.ajaxSubmit(link);
                    e.stopPropagation();
                });
            });

            $(".filter-current a").off('click').on('click', function (e) {
                var link = self.ValidateUrl($(this).prop('href'));
                if(!link) return;

                self.ajaxSubmit(link);
                e.stopPropagation();
                e.preventDefault();
            });

            $(".filter-actions a").off('click').on('click', function (e) {
                var link = self.ValidateUrl($(this).prop('href'));
                if(!link) return;

                self.ajaxSubmit(link);
                e.stopPropagation();
                e.preventDefault();
            });

            $(".toolbar-products .pages .pages-items .item a").off('click').on('click', function (e) {
                var link = self.ValidateUrl($(this).prop('href'));
                if(!link) return;

                self.ajaxSubmit(link);
                e.stopPropagation();
                e.preventDefault();
            });
        },

        initProductList: function () {
            var self = this;
            $.mage.productListToolbarForm.prototype.changeUrl = function (paramName, paramValue, defaultValue) {
                if (running == true){
                    running = false;
                    
                    var urlPaths = this.options.url.split('?'),
                        baseUrl = urlPaths[0],
                        urlParams = urlPaths[1] ? urlPaths[1].split('&') : [],
                        paramData = {},
                        parameters;
                    for (var i = 0; i < urlParams.length; i++) {
                        parameters = urlParams[i].split('=');
                        paramData[parameters[0]] = parameters[1] !== undefined
                            ? window.decodeURIComponent(parameters[1].replace(/\+/g, '%20'))
                            : '';
                    }
                    paramData[paramName] = paramValue;
                    if (paramValue == defaultValue) {
                        delete paramData[paramName];
                    }
                    paramData = $.param(paramData);

                    self.ajaxSubmit(baseUrl + (paramData.length ? '?' + paramData : '')); 
                }else{
                    running = true;
                }
            }
        },

        initClickShopBy: function () {
            if( $(window).width() < 768 ){
                $( ".block.filter .filter-title strong" ).click(function(){
                    if(!$('.page-with-filter').hasClass('filter-active'))
                    {
                        $('.page-with-filter').addClass('filter-active');
                        $('.block.filter').addClass('active');
                    }else{
                        $('.page-with-filter').removeClass('filter-active');
                        $('.block.filter').removeClass('active');
                    }
                });
                
                $( ".block.filter .filter-title strong" ).click();
            }
        },

        ajaxSubmit: function (submitUrl) {
            var self = this;

            $.ajax({
                url: submitUrl,
                data: {isAjax: 1},
                type: 'post',
                dataType: 'json',
                beforeSend: function () {
                    $('body').trigger('processStart');
                    if (typeof window.history.pushState === 'function') {
                        window.history.pushState({url: submitUrl}, '', submitUrl);
                    }
                },
                success: function (data) {
                    if (data.backUrl) {
                        window.location = data.backUrl;
                        return;
                    }
                    if (data.navigation) {
                        $(self.options.navigationSelector).replaceWith(data.navigation);
                        $(self.options.navigationSelector).trigger('contentUpdated');
                    }
                    if (data.products) {
                        $(self.options.productsListSelector).replaceWith(data.products);
                        $(self.options.productsListSelector).trigger('contentUpdated');
                    }

                    $("html, body").animate({ scrollTop: 0 }, "slow");

                    $('body').trigger('processStop');
                    self.filterActive();
                },
                error: function () {
                    window.location.reload();
                }
            });
        },
        ValidateUrl: function (url) {
            var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/; 

            return regex.test(url) ? url : null;
        },
        filterActive: function () {
            var filterCurrent = $('.filter-current .items .item');
            var filterOptions = $('.filter-options');
            filterCurrent.each(function() {
                var code = $(this).find('.filter-label').attr('attribute-code');
                var value = $(this).find('.filter-value').attr('option-id');
                var option = filterOptions.find( '.swatch-attribute[attribute-code="' + code + '"]' + " .swatch-option[option-id='" + value + "']" );
                if(option.length) option.addClass('selected');
            });
        }        
    });

    return $.magepow.layerednav;
});
