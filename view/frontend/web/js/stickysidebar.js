/*
* @Author: Alex Dong
* @Date:   2020-10-17 23:20:18
* @Last Modified by:   Alex Dong
* @Last Modified time: 2020-10-21 11:29:24
*/

define([
    'jquery',
    'jquery/ui',
    'mage/mage',
    'mage/sticky'
], function ($) {
    "use strict";

    $.widget('magepow.stickySidebar', $.mage.sticky, {
        _stick: function () {
            var self = this;
            if($(window).width() < 992) return;
            if (this.element.is(':visible')) {
                var element                 = $(this.element);
                var maincontent             = $('#maincontent');
                var accordion               = $(this.element).find('.accordion-container');
                var sidebarAdditional       = maincontent.find('.sidebar-additional');
                var is3columns              = $('body').hasClass('page-layout-3columns');
                if(is3columns) return false;
                var sidebarHeight           = is3columns ? element.outerHeight(true) : element.outerHeight(true) + sidebarAdditional.outerHeight(true);
                var sidebarAdditionalTop    = is3columns ? this._getOptionValue('spacingTop') : this._getOptionValue('spacingTop') + element.outerHeight(true) - accordion.outerHeight(true);
                var columnMain              = maincontent.find('.column.main');
                var columnMainHeight        = columnMain.outerHeight(true);
                var offset                  = $(document).scrollTop();
                var resetSticky = {'position': '','width': '','top': '','bottom': ''};
                if(sidebarHeight >= columnMainHeight){
                    element.css(resetSticky); 
                    sidebarAdditional.css(resetSticky);
                    return;
                }
                var columnMainTop           = columnMain.offset().top + this._getOptionValue('spacingTop');
                var columnMainBottom        = columnMainTop + columnMainHeight;
                var isBottom                = (offset + sidebarHeight - accordion.outerHeight(true) > columnMainBottom);
                var isFixed                 = (offset > columnMainTop && !isBottom);
                if(!this.sidebarAdditionalWidth) this.sidebarAdditionalWidth = sidebarAdditional.outerWidth(true);
                if(!this.sidebarMainWidth) this.sidebarMainWidth = $(this.element).outerWidth(true);
                if(!this.sidebarMainPadding) this.sidebarMainPadding = element.css('padding-top') + ' ' + element.css('padding-right') + ' ' + element.css('padding-bottom') + ' ' + element.css('padding-left');
                var stuck = this.element.hasClass(this.options.stickyClass);

                $('body').on('contentUpdated', function(){
                    element.css(resetSticky); 
                    sidebarAdditional.css(resetSticky);
                });

                this.element.toggleClass(this.options.stickyClass, offset >= 0);
                sidebarAdditional.toggleClass(this.options.stickyClass, offset >= 0);
                if( isFixed ){
                    this.element.css({
                        'top': this._getOptionValue('spacingTop') - accordion.outerHeight(true),
                        'width': this.sidebarMainWidth,
                        'padding': this.sidebarMainPadding,
                        'position': 'fixed',
                        'bottom': ''
                    });
                    sidebarAdditional.css({
                        'top': sidebarAdditionalTop,
                        'width': this.sidebarAdditionalWidth,
                        'position': 'fixed',
                        'bottom': ''
                    });
                } else if(isBottom){
                    this.element.css({
                        'position': 'absolute',
                        'width': '',
                        'top': 'auto',
                        'bottom': sidebarAdditional.outerHeight(true)
                    });
                    sidebarAdditional.css({
                        'position': 'absolute',
                        'width': '',
                        'top': 'auto',
                        'bottom': '0px',                        
                    });                      
                  
                } else {
                    element.css(resetSticky); 
                    sidebarAdditional.css(resetSticky);                
                }
            }
        }
    });

    return $.magepow.stickySidebar;
});
