/**
 * Lasso Owl Carousel Production Script
 * Minimal logging for production use
 */

(function ($) {
    'use strict';

    // Force initialization function
    function forceInitLassoOwl() {
        // Check jQuery
        if (typeof jQuery === 'undefined') {
            return;
        }

        // Check Owl Carousel
        if (typeof $.fn.owlCarousel === 'undefined') {
            setTimeout(forceInitLassoOwl, 500);
            return;
        }

        // Find Lasso carousels
        var $lassoCarousels = $('.lasso-container .owl-carousel');
        if ($lassoCarousels.length === 0) {
            return;
        }

        // Initialize each carousel
        $lassoCarousels.each(function (index) {
            var $carousel = $(this);

            // Skip if already initialized
            if ($carousel.hasClass('owl-loaded')) {
                return;
            }

            // Skip if no items
            if ($carousel.find('.item').length === 0) {
                return;
            }

            try {
                $carousel.owlCarousel({
                    loop: true,
                    margin: 15,
                    nav: true,
                    dots: true,
                    autoplay: false,
                    autoplayTimeout: 3000,
                    autoplayHoverPause: true,
                    responsive: {
                        0: { items: 1 },
                        600: { items: 2 },
                        1000: { items: 3 }
                    },
                    navText: [
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg>',
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg>'
                    ]
                });
            } catch (e) {
                // Silent fail for production
            }
        });
    }

    // Initialize on DOM ready
    $(document).ready(function () {
        forceInitLassoOwl();
    });

    // Re-initialize on window load (fallback)
    $(window).on('load', function () {
        setTimeout(forceInitLassoOwl, 100);
    });

    // Re-initialize when new content is loaded
    $(document).on('DOMNodeInserted', function (e) {
        if ($(e.target).hasClass('lasso-container') || $(e.target).find('.lasso-container').length) {
            setTimeout(forceInitLassoOwl, 100);
        }
    });

    // For Lasso editor specific events
    if (typeof wp !== 'undefined' && wp.hooks) {
        wp.hooks.addAction('lasso_display_rendered', 'bloggers', function () {
            setTimeout(forceInitLassoOwl, 200);
        });
    }

})(jQuery);
