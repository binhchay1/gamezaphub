/**
 * CSP Safe Gallery Initializer
 * 
 * File này đảm bảo gallery hoạt động mà không vi phạm Content Security Policy
 * bằng cách sử dụng data attributes thay vì inline scripts
 */

(function() {
    'use strict';
    
    // Kiểm tra xem main gallery script đã load chưa
    function waitForGalleryScript() {
        if (typeof window.changeImage === 'function') {
            // Main script đã load, khởi tạo gallery data
            if (typeof window.reinitializeGalleryData === 'function') {
                window.reinitializeGalleryData();
            }
        } else {
            // Chờ main script load
            setTimeout(waitForGalleryScript, 100);
        }
    }
    
    // Khởi tạo khi DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', waitForGalleryScript);
    } else {
        waitForGalleryScript();
    }
    
    // Observer để theo dõi dynamic content
    if (window.MutationObserver) {
        var observer = new MutationObserver(function(mutations) {
            var shouldReinit = false;
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            if (node.classList && node.classList.contains('custom-gallery-container')) {
                                shouldReinit = true;
                            } else if (node.querySelector && node.querySelector('.custom-gallery-container')) {
                                shouldReinit = true;
                            }
                        }
                    });
                }
            });
            
            if (shouldReinit && typeof window.reinitializeGalleryData === 'function') {
                setTimeout(window.reinitializeGalleryData, 100);
            }
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
})();
