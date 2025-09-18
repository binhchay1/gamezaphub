/*
Plugin Name: WP Tab Widget Pro
Plugin URI: http://mythemeshop.com/plugins/wp-tab-widget-pro/
Description: WP Tab Widget is the AJAXified plugin which loads content by demand, and thus it makes the plugin incredibly lightweight.
Author: MyThemeShop
Author URI: http://mythemeshop.com/
*/
function wpt_loadTabContent( tab_name, page_num, container, widget_number, style, preview ) {
    
    var container = jQuery(container);
    var tab_content = container.find('#'+tab_name+'-tab-content');
        
    // only load content if it wasn't already loaded
    var isLoaded = tab_content.data('loaded');
    
    if (!isLoaded || page_num != 1) {
        if (!container.hasClass('wpt-loading')) {
            container.addClass('wpt-loading');
            
            tab_content.load(wpt.ajax_url, {
                    action: 'wpt_widget_content',
                    tab: tab_name,
                    page: page_num,
                    widget_number: widget_number,
                    style: style,
                    preview: preview
                }, function() {
                    container.removeClass('wpt-loading');
                    tab_content.data('loaded', 1).hide().fadeIn().siblings().hide();
                }
            );
        }
    } else {
        tab_content.fadeIn().siblings().hide();
    }
}

jQuery(document).ready(function() {

    function wpt_initTabs() {
        jQuery('.wpt_widget_content, .wptp_widget_content').each(function() {
            var $this = jQuery(this),
                widget_id = this.id,
                widget_number = $this.data('widget-number'),
                style = $this.data('style'),
                preview = '0';

            if ( $this.parent().parent().hasClass('wpt-modal-section') ) {
                preview = '1';
            }
            
            // load tab content on click
            $this.find('.wpt-tabs a, .wptp-tabs a, .wpt_acc_title a').click(function(e) {
                e.preventDefault();
                var $this_a = jQuery(this),
                    this_id = this.id,
                    tab_name = this.id.slice(0, -4); // -tab
                $this_a.parent().addClass('selected').siblings().removeClass('selected');

                if ( $this_a.parent().hasClass('wpt_acc_title') ) {
                    $this.find('.tab_title #'+this_id).parent().addClass('selected').siblings().removeClass('selected');
                } else {
                    $this.find('.wpt_acc_title #'+this_id).parent().addClass('selected').siblings().removeClass('selected');
                }

                wpt_loadTabContent(tab_name, 1, $this, widget_number, style, preview);
            });
            
            // pagination
            $this.on('click', '.wpt-pagination a, .wptp-pagination li a', function(e) {
                e.preventDefault();
                var $this_a = jQuery(this),
                    tab_name = $this_a.closest('.tab-content').attr('id').slice(0, -12), // -tab-content
                    page_num = parseInt($this_a.closest('.tab-content').children('.page_num').val()),
                    $click_num = $this_a.text(),
                    click_num = parseInt($click_num);
                $this.find('#'+tab_name+'-tab-content').data('loaded', 0);
                if ($this_a.hasClass('next')) {
                    wpt_loadTabContent(tab_name, page_num + 1, $this, widget_number, style, preview);
                } else if ($this_a.hasClass('prev')) {
                    wpt_loadTabContent(tab_name, page_num - 1, $this, widget_number, style, preview);
                } else {
                    wpt_loadTabContent(tab_name, click_num, $this, widget_number, style, preview);
                }
            });

            if ( parseInt( $this.width() ) < 250 ) {
                $this.addClass('wpt_acc');
            } else {
                $this.removeClass('wpt_acc');
            }
            
            // load first tab now
            $this.find('.wpt-tabs a, .wptp-tabs a').first().click();
        });
    }

    wpt_initTabs();

    jQuery( window ).on( 'resize', function() {
        jQuery('.wpt_widget_content, .wptp_widget_content').each(function() {
            var $this = jQuery(this);
            if ( parseInt( $this.width() ) < 250 ) {
                $this.addClass('wpt_acc');
            } else {
                $this.removeClass('wpt_acc');
            }
        });
    });
    
    // Admin preview
    jQuery( document ).on( 'wptPreviewLoaded', function( event ) { wpt_initTabs(); });
});