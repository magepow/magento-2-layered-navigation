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
                var sidebarHeight           = is3columns ? element.outerHeight() : element.outerHeight() + sidebarAdditional.outerHeight();
                var sidebarAdditionalTop    = is3columns ? this._getOptionValue('spacingTop') : element.outerHeight() - accordion.height();
                var columnMain              = maincontent.find('.column.main');
                var columnMainHeight        = columnMain.outerHeight();
                var offset                  = $(document).scrollTop();
                var resetSticky = {'position': '','width': '','top': '','bottom': ''};
                if(sidebarHeight >= columnMainHeight){
                    element.css(resetSticky); 
                    sidebarAdditional.css(resetSticky);
                    return;
                }
                var columnMainTop           = columnMain.offset().top + this._getOptionValue('spacingTop');
                var columnMainBottom        = columnMainTop + columnMainHeight;
                var isBottom                = (offset + sidebarHeight - accordion.outerHeight() > columnMainBottom);
                var isFixed                 = (offset > columnMainTop && !isBottom);
                if(!this.sidebarAdditionalWidth) this.sidebarAdditionalWidth = sidebarAdditional.outerWidth();
                if(!this.sidebarMainWidth) this.sidebarMainWidth = $(this.element).outerWidth();
                var stuck = this.element.hasClass(this.options.stickyClass);

                $('body').on('contentUpdated', function(){
                    element.css(resetSticky); 
                    sidebarAdditional.css(resetSticky);
                });

                this.element.toggleClass(this.options.stickyClass, offset >= 0);
                sidebarAdditional.toggleClass(this.options.stickyClass, offset >= 0);
                if( isFixed ){
                    this.element.css({
                        'top': this._getOptionValue('spacingTop') - accordion.outerHeight(),
                        'width': this.sidebarMainWidth,
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
                        'bottom': sidebarAdditional.outerHeight()
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
