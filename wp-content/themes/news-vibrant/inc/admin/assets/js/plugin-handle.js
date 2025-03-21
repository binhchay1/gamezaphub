/**
 * Get Started button on dashboard notice.
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.5.2
 */

jQuery(document).ready(function($) {
    var WpAjaxurl       = cvAdminObject.ajax_url;
    var _wpnonce        = cvAdminObject._wpnonce;
    var buttonStatus    = cvAdminObject.buttonStatus;

    /**
     * Popup on click demo import if cv demo importer plugin is not activated.
     */
    if( buttonStatus === 'disable' ) $( '.news-vibrant-demo-import' ).addClass( 'disabled' );

    switch( buttonStatus ) {
        case 'activate' :
            $( '.news-vibrant-get-started' ).on( 'click', function() {
                var _this = $( this );
                news_vibrant_do_plugin( 'news_vibrant_activate_plugin', _this );
            });
            $( '.news-vibrant-activate-demo-import-plugin' ).on( 'click', function() {
                var _this = $( this );
                news_vibrant_do_plugin( 'news_vibrant_activate_plugin', _this );
            });
            break;
        case 'install' :
            $( '.news-vibrant-get-started' ).on( 'click', function() {
                var _this = $( this );
                news_vibrant_do_plugin( 'news_vibrant_install_plugin', _this );
            });
            $( '.news-vibrant-install-demo-import-plugin' ).on( 'click', function() {
                var _this = $( this );
                news_vibrant_do_plugin( 'news_vibrant_install_plugin', _this );
            });
            break;
        case 'redirect' :
            $( '.news-vibrant-get-started' ).on( 'click', function() {
                var _this = $( this );
                location.href = _this.data( 'redirect' );
            });
            break;
    }
    
    news_vibrant_do_plugin = function ( ajax_action, _this ) {
        $.ajax({
            method : "POST",
            url : WpAjaxurl,
            data : ({
                'action' : ajax_action,
                '_wpnonce' : _wpnonce
            }),
            beforeSend: function() {
                var loadingTxt = _this.data( 'process' );
                _this.addClass( 'updating-message' ).text( loadingTxt );
            },
            success: function( response ) {
                if( response.success ) {
                    var loadedTxt = _this.data( 'done' );
                    _this.removeClass( 'updating-message' ).text( loadedTxt );
                }
                location.href = _this.data( 'redirect' );
            }
        });
    }

    $('.cv-action-btn').click(function(){
        var _this = $(this), actionBtnStatus = _this.data('status'), pluginSlug = _this.data('slug');
        console.log(actionBtnStatus);
        switch(actionBtnStatus){
            case 'install':
                news_vibrant_do_free_plugin( 'news_vibrant_install_free_plugin', pluginSlug, _this );
                break;

            case 'active':
                news_vibrant_do_free_plugin( 'news_vibrant_activate_free_plugin', pluginSlug, _this );
                break;
        }

    });

    news_vibrant_do_free_plugin = function ( ajax_action, pluginSlug, _this ) {
        $.ajax({
            method : "POST",
            url : WpAjaxurl,
            data : ({
                'action' : ajax_action,
                'plugin_slug': pluginSlug,
                '_wpnonce' : _wpnonce
            }),
            beforeSend: function() {
                var loadingTxt = _this.data( 'process' );
                _this.addClass( 'updating-message' ).text( loadingTxt );
            },
            success: function( response ) {
                if( response.success ) {
                    var loadedTxt = _this.data( 'done' );
                    _this.removeClass( 'updating-message' ).text( loadedTxt );
                }
                location.href = _this.data( 'redirect' );
            }
        });
    }

});