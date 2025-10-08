<?php
/**
 * Lasso Owl Carousel Fix
 * Fix Owl Carousel not initializing in Lasso layout-6-box
 * 
 * @package Bloggers
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue Owl Carousel init script for Lasso displays
 * Note: Owl Carousel CSS/JS is already loaded by theme
 */
function bloggers_enqueue_owl_carousel_for_lasso() {
    // Only load on frontend pages
    if (!is_admin()) {
        // Add init script to existing owl carousel
        wp_add_inline_script('bloggers-owl-js', bloggers_owl_carousel_init_script(), 'after');
    }
}
add_action('wp_enqueue_scripts', 'bloggers_enqueue_owl_carousel_for_lasso', 999);

/**
 * Get Owl Carousel initialization script
 */
function bloggers_owl_carousel_init_script() {
    return "
    jQuery(document).ready(function($) {
        // Initialize Owl Carousel for Lasso displays
        function initLassoOwlCarousel() {
            // Check if Owl Carousel is loaded
            if (typeof $.fn.owlCarousel !== 'function') {
                console.log('Owl Carousel not loaded yet, retrying...');
                setTimeout(initLassoOwlCarousel, 100);
                return;
            }
            
            // Find all owl carousel instances in Lasso displays
            $('.lasso-container .owl-carousel').each(function() {
                var \$carousel = $(this);
                
                // Check if already initialized
                if (\$carousel.hasClass('owl-loaded')) {
                    return;
                }
                
                // Initialize with settings
                \$carousel.owlCarousel({
                    loop: true,
                    margin: 15,
                    nav: true,
                    dots: true,
                    autoplay: false,
                    autoplayTimeout: 3000,
                    autoplayHoverPause: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 2
                        },
                        1000: {
                            items: 3
                        }
                    },
                    navText: [
                        '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" fill=\"currentColor\" viewBox=\"0 0 16 16\"><path fill-rule=\"evenodd\" d=\"M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z\"/></svg>',
                        '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" fill=\"currentColor\" viewBox=\"0 0 16 16\"><path fill-rule=\"evenodd\" d=\"M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z\"/></svg>'
                    ]
                });
                
                console.log('Owl Carousel initialized for Lasso display');
            });
        }
        
        // Initialize on page load
        initLassoOwlCarousel();
        
        // Re-initialize when new content is loaded (for AJAX, etc.)
        $(document).on('DOMNodeInserted', function(e) {
            if ($(e.target).hasClass('lasso-container') || $(e.target).find('.lasso-container').length) {
                setTimeout(initLassoOwlCarousel, 100);
            }
        });
        
        // For Lasso editor
        if (typeof wp !== 'undefined' && wp.hooks) {
            wp.hooks.addAction('lasso_display_rendered', 'bloggers', function() {
                setTimeout(initLassoOwlCarousel, 200);
            });
        }
    });
    ";
}

/**
 * Add custom CSS for Owl Carousel in Lasso
 */
function bloggers_lasso_owl_custom_css() {
    ?>
    <style>
    /* Lasso Owl Carousel Custom Styles */
    .lasso-container .owl-carousel,
    .footer-box-lasso .owl-carousel {
        position: relative;
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
    
    .lasso-container .owl-carousel .item,
    .footer-box-lasso .owl-carousel .item {
        position: relative;
        display: block !important;
        visibility: visible !important;
    }
    
    .lasso-container .owl-carousel .item img,
    .footer-box-lasso .owl-carousel .item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: block !important;
        visibility: visible !important;
    }
    
    .lasso-container .owl-carousel .item:hover img {
        transform: scale(1.05);
    }
    
    .lasso-container .owl-nav {
        position: absolute;
        top: 50%;
        width: 100%;
        transform: translateY(-50%);
        display: flex;
        justify-content: space-between;
        pointer-events: none;
        z-index: 10;
    }
    
    .lasso-container .owl-nav button {
        width: 40px;
        height: 40px;
        background: rgba(102, 126, 234, 0.8) !important;
        color: white !important;
        border: none !important;
        border-radius: 50%;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: all;
        transition: all 0.3s ease;
        margin: 0 10px;
    }
    
    .lasso-container .owl-nav button:hover {
        background: rgba(102, 126, 234, 1) !important;
        transform: scale(1.1);
    }
    
    .lasso-container .owl-nav button svg {
        width: 24px;
        height: 24px;
    }
    
    .lasso-container .owl-dots {
        text-align: center;
        margin-top: 15px;
    }
    
    .lasso-container .owl-dot {
        width: 12px;
        height: 12px;
        background: #e9ecef !important;
        border-radius: 50%;
        margin: 0 5px;
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .lasso-container .owl-dot.active,
    .lasso-container .owl-dot:hover {
        background: #667eea !important;
        transform: scale(1.2);
    }
    
    /* Fix for visibility */
    .lasso-container .footer-box-lasso,
    .footer-box-lasso {
        position: relative;
        min-height: 250px;
        display: block !important;
    }
    
    /* Make sure owl carousel is visible */
    .lasso-container .owl-carousel.owl-hidden,
    .footer-box-lasso .owl-carousel.owl-hidden,
    .owl-carousel.owl-hidden {
        opacity: 1 !important;
        display: block !important;
        visibility: visible !important;
    }
    
    /* Force visibility for all owl carousel elements */
    .owl-carousel,
    .owl-carousel .owl-stage-outer,
    .owl-carousel .owl-stage,
    .owl-carousel .owl-item {
        display: block !important;
        visibility: visible !important;
    }
    
    /* Fix Lasso specific styles */
    .lasso-container .owl-stage-outer,
    .footer-box-lasso .owl-stage-outer {
        position: relative;
        overflow: hidden;
        -webkit-transform: translate3d(0, 0, 0);
    }
    
    .lasso-container .owl-item,
    .footer-box-lasso .owl-item {
        position: relative;
        min-height: 1px;
        float: left;
        -webkit-backface-visibility: hidden;
        -webkit-tap-highlight-color: transparent;
        -webkit-touch-callout: none;
    }
    
    /* Responsive fixes */
    @media (max-width: 768px) {
        .lasso-container .owl-carousel .item img {
            height: 150px;
        }
        
        .lasso-container .owl-nav button {
            width: 35px;
            height: 35px;
            margin: 0 5px;
        }
    }
    </style>
    <?php
}
add_action('wp_head', 'bloggers_lasso_owl_custom_css', 100);

/**
 * Debug helper - log Owl Carousel status
 */
function bloggers_owl_debug_script() {
    if (!is_user_logged_in() || !current_user_can('administrator')) {
        return;
    }
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Debug info
        setTimeout(function() {
            console.log('=== Lasso Owl Carousel Debug ===');
            console.log('jQuery loaded:', typeof $ !== 'undefined');
            console.log('Owl Carousel loaded:', typeof $.fn.owlCarousel !== 'undefined');
            console.log('Lasso containers found:', $('.lasso-container').length);
            console.log('Owl carousels found:', $('.lasso-container .owl-carousel').length);
            console.log('Initialized carousels:', $('.lasso-container .owl-carousel.owl-loaded').length);
            console.log('==============================');
        }, 2000);
    });
    </script>
    <?php
}
add_action('wp_footer', 'bloggers_owl_debug_script', 999);
