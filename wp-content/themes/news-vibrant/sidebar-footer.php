<?php
/**
 * The Sidebar containing the footer widget areas.
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

$news_vibrant_footer_widget_option = get_theme_mod( 'news_vibrant_footer_widget_option', true );

if ( false == $news_vibrant_footer_widget_option ) {
    return;
}

    /**
     * The footer widget area is triggered if any of the areas
     * have widgets. So let's check that first.
     *
     * If none of the sidebars have widgets, then let's bail early.
     */
     
    if ( !is_active_sidebar( 'news_vibrant_footer_sidebar' ) &&
        !is_active_sidebar( 'news_vibrant_footer_sidebar-2' ) &&
        !is_active_sidebar( 'news_vibrant_footer_sidebar-3' ) &&
        !is_active_sidebar( 'news_vibrant_footer_sidebar-4' ) ) {
            return;
    }
    $news_vibrant_footer_layout = get_theme_mod( 'footer_widget_layout', 'column_three' );
?>

<div id="top-footer" class="footer-widgets-wrapper footer_<?php echo esc_attr( $news_vibrant_footer_layout ); ?> nv-clearfix">
    <div class="cv-container">
        <div class="footer-widgets-area nv-clearfix">
            <div class="nv-footer-widget-wrapper nv-column-wrapper nv-clearfix">
                
                <div class="nv-footer-widget wow fadeInLeft" data-wow-duration="0.5s">
                    <?php dynamic_sidebar( 'news_vibrant_footer_sidebar' ); ?>
                </div>

                <?php if ( $news_vibrant_footer_layout != 'column_one' ){ ?>
                    <div class="nv-footer-widget wow fadeInLeft" data-woww-duration="1s">
                        <?php dynamic_sidebar( 'news_vibrant_footer_sidebar-2' ); ?>
                    </div>
                <?php } ?>

                <?php if ( $news_vibrant_footer_layout == 'column_three' || $news_vibrant_footer_layout == 'column_four' ){ ?>
                    <div class="nv-footer-widget wow fadeInLeft" data-wow-duration="1.5s">
                        <?php dynamic_sidebar( 'news_vibrant_footer_sidebar-3' ); ?>
                    </div>
                <?php } ?>

                <?php if ( $news_vibrant_footer_layout == 'column_four' ){ ?>
                    <div class="nv-footer-widget wow fadeInLeft" data-wow-duration="2s">
                        <?php dynamic_sidebar( 'news_vibrant_footer_sidebar-4' ); ?>
                    </div>
                <?php } ?>

            </div><!-- .nv-footer-widget-wrapper -->
        </div><!-- .footer-widgets-area -->
    </div><!-- .cv-container -->
</div><!-- .footer-widgets-wrapper -->