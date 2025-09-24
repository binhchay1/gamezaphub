(function () {
    'use strict';

    // Cache để tránh truy vấn DOM nhiều lần - Tương thích ES5
    var cache = {};
    
    // Debounce function để tối ưu hóa performance - Tương thích ES5
    function debounce(func, wait) {
        var timeout;
        return function() {
            var context = this;
            var args = arguments;
            var later = function() {
                clearTimeout(timeout);
                func.apply(context, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // RequestAnimationFrame wrapper để tối ưu hóa animation
    function requestAnimationFrameOptimized(callback) {
        return requestAnimationFrame(function() {
            requestAnimationFrame(callback);
        });
    }

    // Cache DOM elements để tránh truy vấn lại
    function getCachedElements(galleryId) {
        if (!cache[galleryId]) {
            var container = document.getElementById(galleryId);
            if (!container) return null;
            
            cache[galleryId] = {
                container: container,
                mainImg: container.querySelector('.main-gallery-image'),
                thumbnails: container.querySelectorAll('.thumbnail'),
                wrapper: container.querySelector('.thumbnail-wrapper'),
                thumbnailContainer: container.querySelector('.thumbnail-container')
            };
        }
        return cache[galleryId];
    }

    // Tối ưu hóa hàm changeImage
    window.changeImage = function (galleryId, index) {
        var galleryData = window.galleryData && window.galleryData[galleryId];
        if (!galleryData) return;

        var elements = getCachedElements(galleryId);
        if (!elements) return;

        var mainImg = elements.mainImg;
        var thumbnails = elements.thumbnails;

        if (!mainImg || !thumbnails.length) return;

        var newIndex;

        if (typeof index === 'number') {
            if (index === -1) {
                newIndex = Math.max(0, galleryData.currentIndex - 1);
            } else if (index === 1) {
                newIndex = Math.min(galleryData.images.length - 1, galleryData.currentIndex + 1);
            } else {
                newIndex = Math.max(0, Math.min(index, galleryData.images.length - 1));
            }
        } else {
            newIndex = galleryData.currentIndex;
        }

        galleryData.currentIndex = newIndex;

        var currentImage = galleryData.images[newIndex];
        
        // Sử dụng requestAnimationFrame để tối ưu hóa DOM updates
        requestAnimationFrameOptimized(function() {
            mainImg.src = currentImage.url;
            mainImg.alt = currentImage.alt;
            mainImg.setAttribute('data-index', newIndex);

            // Batch DOM updates
            for (var i = 0; i < thumbnails.length; i++) {
                var thumb = thumbnails[i];
                if (i === newIndex) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            }

            // Delay thumbnail position adjustment để tránh forced reflow
            setTimeout(function() {
                adjustThumbnailPositionOptimized(galleryId, newIndex);
            }, 0);
        });
    };

    // Tối ưu hóa hàm openModal
    window.openModal = function (galleryId, index) {
        var galleryData = window.galleryData && window.galleryData[galleryId];
        if (!galleryData) return;

        var modal = document.getElementById('modal-' + galleryId);
        var modalImg = modal && modal.querySelector('.modal-content-img');

        if (!modal || !modalImg) return;

        var imageIndex = typeof index === 'number' ? index : galleryData.currentIndex;
        var currentImage = galleryData.images[imageIndex];

        // Sử dụng requestAnimationFrame để tối ưu hóa
        requestAnimationFrameOptimized(function() {
            modalImg.src = currentImage.url;
            modalImg.alt = currentImage.alt;

            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    };

    window.closeModal = function (galleryId) {
        var modal = document.getElementById('modal-' + galleryId);
        if (modal) {
            requestAnimationFrameOptimized(function() {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            });
        }
    };

    // Tối ưu hóa hàm adjustThumbnailPosition - tránh forced reflow
    function adjustThumbnailPositionOptimized(galleryId, currentIndex) {
        var galleryData = window.galleryData && window.galleryData[galleryId];
        if (!galleryData) return;

        var elements = getCachedElements(galleryId);
        if (!elements) return;

        var wrapper = elements.wrapper;
        var thumbnailContainer = elements.thumbnailContainer;
        var thumbnails = elements.thumbnails;

        if (!wrapper || !thumbnailContainer || !thumbnails.length) return;

        var activeThumb = thumbnails[currentIndex];
        if (!activeThumb) return;

        // Batch tất cả DOM reads trước khi thực hiện bất kỳ DOM writes nào
        var wrapperRect = wrapper.getBoundingClientRect();
        var containerRect = thumbnailContainer.getBoundingClientRect();
        var activeThumbRect = activeThumb.getBoundingClientRect();
        
        // Cache các giá trị cần thiết
        var wrapperWidth = wrapperRect.width;
        var contentWidth = containerRect.width;
        var maxOffsetPx = Math.max(0, contentWidth - wrapperWidth);

        // Lấy transform hiện tại một cách an toàn
        var computed = getComputedStyle(thumbnailContainer).transform;
        var currentOffsetPx = 0;
        
        if (computed && computed !== 'none') {
            var matrix = computed.match(/matrix([^)]+)/);
            if (matrix && matrix[1]) {
                var values = matrix[1].split(',').map(function(v) { return parseFloat(v); });
                if (values.length >= 6) {
                    var tx = values[4];
                    currentOffsetPx = Math.abs(tx) || 0;
                }
            }
        }

        var thumbLeft = activeThumbRect.left - containerRect.left;
        var thumbRight = thumbLeft + activeThumbRect.width;

        var newOffsetPx = currentOffsetPx;
        var padding = 6;

        if (thumbLeft < currentOffsetPx) {
            newOffsetPx = Math.max(0, thumbLeft - padding);
        } else if (thumbRight > currentOffsetPx + wrapperWidth) {
            newOffsetPx = Math.min(maxOffsetPx, thumbRight - wrapperWidth + padding);
        }

        if (newOffsetPx === currentOffsetPx) return;

        // Sử dụng transform3d để kích hoạt hardware acceleration
        requestAnimationFrameOptimized(function() {
            thumbnailContainer.style.transform = 'translate3d(-' + newOffsetPx + 'px, 0, 0)';
            thumbnailContainer.style.willChange = 'transform';
        });
    }

    // Tối ưu hóa event listeners
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('gallery-modal')) {
            var galleryId = e.target.id.replace('modal-', '');
            window.closeModal(galleryId);
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            var openModals = document.querySelectorAll('.gallery-modal.show');
            for (var i = 0; i < openModals.length; i++) {
                var modal = openModals[i];
                var galleryId = modal.id.replace('modal-', '');
                window.closeModal(galleryId);
            }
        }
    });

    // Tối ưu hóa resize handler với debounce
    var handleResizeOptimized = debounce(function() {
        var galleryIds = Object.keys(window.galleryData || {});
        for (var i = 0; i < galleryIds.length; i++) {
            var galleryId = galleryIds[i];
            var galleryData = window.galleryData[galleryId];
            if (galleryData) {
                var screenWidth = window.innerWidth;
                var itemsPerView;
                
                if (screenWidth <= 480) {
                    itemsPerView = 3;
                } else if (screenWidth <= 768) {
                    itemsPerView = 4;
                } else {
                    itemsPerView = 5;
                }

                if (galleryData.itemsPerView !== itemsPerView) {
                    galleryData.itemsPerView = itemsPerView;
                    // Clear cache khi thay đổi layout
                    delete cache[galleryId];
                    
                    // Delay để tránh forced reflow
                    requestAnimationFrameOptimized(function() {
                        adjustThumbnailPositionOptimized(galleryId, galleryData.currentIndex);
                    });
                }
            }
        }
    }, 150);

    window.addEventListener('resize', handleResizeOptimized);

    // Khởi tạo gallery data từ data attributes
    function initializeGalleryData() {
        var galleries = document.querySelectorAll('.custom-gallery-container[data-gallery-images]');
        window.galleryData = window.galleryData || {};
        
        for (var i = 0; i < galleries.length; i++) {
            var container = galleries[i];
            var galleryId = container.getAttribute('data-gallery-id');
            
            if (galleryId && !window.galleryData[galleryId]) {
                try {
                    var images = JSON.parse(container.getAttribute('data-gallery-images'));
                    var currentIndex = parseInt(container.getAttribute('data-gallery-current')) || 0;
                    var itemsPerView = parseInt(container.getAttribute('data-gallery-items-per-view')) || 5;
                    
                    window.galleryData[galleryId] = {
                        images: images,
                        currentIndex: currentIndex,
                        itemsPerView: itemsPerView
                    };
                } catch (e) {
                    console.warn('Error parsing gallery data for', galleryId, e);
                }
            }
        }
    }

    // Tối ưu hóa DOMContentLoaded handler
    document.addEventListener('DOMContentLoaded', function () {
        // Khởi tạo gallery data trước
        initializeGalleryData();
        
        // Delay initial setup để tránh blocking
        requestAnimationFrameOptimized(function() {
            handleResizeOptimized();

            var galleryIds = Object.keys(window.galleryData || {});
            for (var i = 0; i < galleryIds.length; i++) {
                var galleryId = galleryIds[i];
                var container = document.getElementById(galleryId);
                if (container) {
                    var images = container.querySelectorAll('img');
                    var loadedCount = 0;
                    var totalImages = images.length;

                    if (totalImages === 0) {
                        container.classList.remove('loading');
                        continue;
                    }

                    // Sử dụng Intersection Observer để lazy load (với fallback)
                    if (window.IntersectionObserver) {
                        var imageObserver = new IntersectionObserver(function(entries) {
                            for (var j = 0; j < entries.length; j++) {
                                var entry = entries[j];
                                if (entry.isIntersecting) {
                                    var img = entry.target;
                                    img.addEventListener('load', function () {
                                        loadedCount++;
                                        if (loadedCount === totalImages) {
                                            container.classList.remove('loading');
                                            imageObserver.disconnect();
                                        }
                                    });

                                    img.addEventListener('error', function () {
                                        loadedCount++;
                                        if (loadedCount === totalImages) {
                                            container.classList.remove('loading');
                                            imageObserver.disconnect();
                                        }
                                    });
                                }
                            }
                        }, {
                            rootMargin: '50px'
                        });

                        for (var k = 0; k < images.length; k++) {
                            imageObserver.observe(images[k]);
                        }
                    } else {
                        // Fallback cho trình duyệt không hỗ trợ IntersectionObserver
                        for (var l = 0; l < images.length; l++) {
                            var img = images[l];
                            img.addEventListener('load', function () {
                                loadedCount++;
                                if (loadedCount === totalImages) {
                                    container.classList.remove('loading');
                                }
                            });

                            img.addEventListener('error', function () {
                                loadedCount++;
                                if (loadedCount === totalImages) {
                                    container.classList.remove('loading');
                                }
                            });
                        }
                    }
                }
            }
        });
    });

    // Function để re-initialize gallery data (cho dynamic content)
    window.reinitializeGalleryData = function() {
        initializeGalleryData();
    };

    // Cleanup function để giải phóng memory
    window.addEventListener('beforeunload', function() {
        cache = {};
    });

})();