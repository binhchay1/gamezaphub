/**
 * Performance Optimizer - Prevent Forced Reflows
 * Tối ưu hiệu suất, giảm forced reflow từ mọi nguồn
 * 
 * Techniques:
 * 1. Batch DOM reads/writes globally
 * 2. Use Intersection Observer instead of scroll events
 * 3. Throttle/debounce expensive operations
 * 4. Cache geometric calculations
 * 5. Optimize third-party library initialization
 */

(function () {
    'use strict';

    // =========================================================================
    // Global Performance Utilities
    // =========================================================================

    /**
     * FastDOM-like batching for reads and writes
     */
    const scheduler = {
        reads: [],
        writes: [],
        scheduled: false,

        measure(fn) {
            this.reads.push(fn);
            this.scheduleFlush();
        },

        mutate(fn) {
            this.writes.push(fn);
            this.scheduleFlush();
        },

        scheduleFlush() {
            if (this.scheduled) return;
            this.scheduled = true;

            requestAnimationFrame(() => {
                this.flush();
            });
        },

        flush() {
            let task;
            while ((task = this.reads.shift())) {
                task();
            }

            while ((task = this.writes.shift())) {
                task();
            }

            this.scheduled = false;
        }
    };

    window.performanceScheduler = scheduler;

    /**
     * Advanced throttle with leading and trailing execution
     */
    function throttle(func, wait, options = {}) {
        let timeout, context, args, result;
        let previous = 0;

        const later = function () {
            previous = options.leading === false ? 0 : Date.now();
            timeout = null;
            result = func.apply(context, args);
            if (!timeout) context = args = null;
        };

        const throttled = function () {
            const now = Date.now();
            if (!previous && options.leading === false) previous = now;
            const remaining = wait - (now - previous);
            context = this;
            args = arguments;

            if (remaining <= 0 || remaining > wait) {
                if (timeout) {
                    clearTimeout(timeout);
                    timeout = null;
                }
                previous = now;
                result = func.apply(context, args);
                if (!timeout) context = args = null;
            } else if (!timeout && options.trailing !== false) {
                timeout = setTimeout(later, remaining);
            }
            return result;
        };

        throttled.cancel = function () {
            clearTimeout(timeout);
            previous = 0;
            timeout = context = args = null;
        };

        return throttled;
    }

    /**
     * Debounce with immediate option
     */
    function debounce(func, wait, immediate = false) {
        let timeout;
        return function () {
            const context = this;
            const args = arguments;
            const later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    // =========================================================================
    // Optimize Scroll Performance
    // =========================================================================

    /**
     * Replace scroll events with passive listeners + RAF
     */
    let ticking = false;
    let lastScrollY = window.pageYOffset;

    function onScroll() {
        lastScrollY = window.pageYOffset;

        if (!ticking) {
            window.requestAnimationFrame(() => {
                scheduler.measure(() => {
                    const scrollY = lastScrollY;
                    const event = new CustomEvent('optimizedScroll', {
                        detail: { scrollY }
                    });
                    window.dispatchEvent(event);
                });

                ticking = false;
            });

            ticking = true;
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });

    // =========================================================================
    // Optimize Resize Performance
    // =========================================================================

    /**
     * Debounced resize with viewport size caching
     */
    let cachedViewport = {
        width: window.innerWidth,
        height: window.innerHeight
    };

    const optimizedResize = debounce(() => {
        scheduler.measure(() => {
            const newWidth = window.innerWidth;
            const newHeight = window.innerHeight;

            if (newWidth !== cachedViewport.width || newHeight !== cachedViewport.height) {
                cachedViewport.width = newWidth;
                cachedViewport.height = newHeight;

                const event = new CustomEvent('optimizedResize', {
                    detail: cachedViewport
                });
                window.dispatchEvent(event);
            }
        });
    }, 200);

    window.addEventListener('resize', optimizedResize, { passive: true });

    // =========================================================================
    // Intersection Observer for Lazy Loading
    // =========================================================================

    /**
     * Replace scroll-based visibility checks with IntersectionObserver
     */
    if ('IntersectionObserver' in window) {
        const observerOptions = {
            root: null,
            rootMargin: '50px',
            threshold: [0, 0.25, 0.5, 0.75, 1]
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const event = new CustomEvent('elementVisible', {
                        detail: { element: target, ratio: entry.intersectionRatio }
                    });
                    target.dispatchEvent(event);
                }
            });
        }, observerOptions);

        window.observeElement = function (element) {
            if (element && element.nodeType === 1) {
                observer.observe(element);
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-observe]').forEach(el => {
                observer.observe(el);
            });
        });
    }

    // =========================================================================
    // Optimize Owl Carousel Initialization
    // =========================================================================

    /**
     * Delay Owl Carousel init to prevent blocking
     */
    function optimizeOwlCarousel() {
        if (typeof jQuery === 'undefined' || !jQuery.fn.owlCarousel) {
            return;
        }

        if ('requestIdleCallback' in window) {
            requestIdleCallback(() => {
                initOwlCarousels();
            }, { timeout: 2000 });
        } else {
            setTimeout(initOwlCarousels, 100);
        }
    }

    function initOwlCarousels() {
        const carousels = document.querySelectorAll('.owl-carousel:not(.owl-loaded)');
        if (carousels.length === 0) return;

        scheduler.measure(() => {
            const config = {
                loop: true,
                margin: 10,
                nav: true,
                lazyLoad: true,
                autoplayHoverPause: true,
                responsive: {
                    0: { items: 1 },
                    600: { items: 3 },
                    1000: { items: 5 }
                }
            };

            scheduler.mutate(() => {
                jQuery('.owl-carousel:not(.owl-loaded)').owlCarousel(config);
            });
        });
    }

    // =========================================================================
    // Cache Frequently Accessed DOM Properties
    // =========================================================================

    const geometryCache = new Map();

    /**
     * Get cached element dimensions
     */
    window.getCachedDimensions = function (element, forceUpdate = false) {
        if (!element) return null;

        const cacheKey = element.dataset.cacheId || Math.random().toString(36);
        element.dataset.cacheId = cacheKey;

        if (!forceUpdate && geometryCache.has(cacheKey)) {
            return geometryCache.get(cacheKey);
        }

        let dimensions = null;

        scheduler.measure(() => {
            dimensions = {
                width: element.offsetWidth,
                height: element.offsetHeight,
                top: element.offsetTop,
                left: element.offsetLeft,
                scrollHeight: element.scrollHeight,
                scrollWidth: element.scrollWidth
            };

            geometryCache.set(cacheKey, dimensions);
        });

        return dimensions;
    };

    window.addEventListener('optimizedResize', () => {
        geometryCache.clear();
    });

    // =========================================================================
    // Prevent Layout Thrashing from Parent Theme
    // =========================================================================

    /**
     * Wrap common DOM methods to batch operations.
     */
    function wrapDOMMethod(obj, method, type) {
        const original = obj[method];

        obj[method] = function (...args) {
            if (type === 'read') {
                let result;
                scheduler.measure(() => {
                    result = original.apply(this, args);
                });
                return result;
            } else {
                scheduler.mutate(() => {
                    original.apply(this, args);
                });
            }
        };
    }

    // =========================================================================
    // Initialize on DOM ready
    // =========================================================================

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        optimizeOwlCarousel();

        scheduler.measure(() => {
            cachedViewport.width = window.innerWidth;
            cachedViewport.height = window.innerHeight;
        });

        if (window.console && console.log) {
            console.log('⚡ Performance Optimizer: Loaded & Active');
        }
    }

    // =========================================================================
    // Expose Public API
    // =========================================================================

    window.PerformanceOptimizer = {
        scheduler,
        throttle,
        debounce,
        getCachedDimensions,
        observeElement: window.observeElement || function () { }
    };

})();

