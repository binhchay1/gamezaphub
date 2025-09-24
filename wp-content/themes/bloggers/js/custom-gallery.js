(function () {
    'use strict';

    // Cache để tránh truy vấn DOM nhiều lần
    const cache = new Map();
    
    // Debounce function để tối ưu hóa performance
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // RequestAnimationFrame wrapper để tối ưu hóa animation
    function requestAnimationFrameOptimized(callback) {
        return requestAnimationFrame(() => {
            requestAnimationFrame(callback);
        });
    }

    // Cache DOM elements để tránh truy vấn lại
    function getCachedElements(galleryId) {
        if (!cache.has(galleryId)) {
            const container = document.getElementById(galleryId);
            if (!container) return null;
            
            cache.set(galleryId, {
                container,
                mainImg: container.querySelector('.main-gallery-image'),
                thumbnails: container.querySelectorAll('.thumbnail'),
                wrapper: container.querySelector('.thumbnail-wrapper'),
                thumbnailContainer: container.querySelector('.thumbnail-container')
            });
        }
        return cache.get(galleryId);
    }

    // Tối ưu hóa hàm changeImage
    window.changeImage = function (galleryId, index) {
        const galleryData = window.galleryData && window.galleryData[galleryId];
        if (!galleryData) return;

        const elements = getCachedElements(galleryId);
        if (!elements) return;

        const { mainImg, thumbnails } = elements;

        if (!mainImg || !thumbnails.length) return;

        let newIndex;

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

        const currentImage = galleryData.images[newIndex];
        
        // Sử dụng requestAnimationFrame để tối ưu hóa DOM updates
        requestAnimationFrameOptimized(() => {
            mainImg.src = currentImage.url;
            mainImg.alt = currentImage.alt;
            mainImg.setAttribute('data-index', newIndex);

            // Batch DOM updates
            thumbnails.forEach((thumb, i) => {
                thumb.classList.toggle('active', i === newIndex);
            });

            // Delay thumbnail position adjustment để tránh forced reflow
            setTimeout(() => {
                adjustThumbnailPositionOptimized(galleryId, newIndex);
            }, 0);
        });
    };

    // Tối ưu hóa hàm openModal
    window.openModal = function (galleryId, index) {
        const galleryData = window.galleryData && window.galleryData[galleryId];
        if (!galleryData) return;

        const modal = document.getElementById('modal-' + galleryId);
        const modalImg = modal?.querySelector('.modal-content-img');

        if (!modal || !modalImg) return;

        const imageIndex = typeof index === 'number' ? index : galleryData.currentIndex;
        const currentImage = galleryData.images[imageIndex];

        // Sử dụng requestAnimationFrame để tối ưu hóa
        requestAnimationFrameOptimized(() => {
            modalImg.src = currentImage.url;
            modalImg.alt = currentImage.alt;

            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    };

    window.closeModal = function (galleryId) {
        const modal = document.getElementById('modal-' + galleryId);
        if (modal) {
            requestAnimationFrameOptimized(() => {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            });
        }
    };

    // Tối ưu hóa hàm adjustThumbnailPosition - tránh forced reflow
    function adjustThumbnailPositionOptimized(galleryId, currentIndex) {
        const galleryData = window.galleryData && window.galleryData[galleryId];
        if (!galleryData) return;

        const elements = getCachedElements(galleryId);
        if (!elements) return;

        const { wrapper, thumbnailContainer, thumbnails } = elements;

        if (!wrapper || !thumbnailContainer || !thumbnails.length) return;

        const activeThumb = thumbnails[currentIndex];
        if (!activeThumb) return;

        // Batch tất cả DOM reads trước khi thực hiện bất kỳ DOM writes nào
        const wrapperRect = wrapper.getBoundingClientRect();
        const containerRect = thumbnailContainer.getBoundingClientRect();
        const activeThumbRect = activeThumb.getBoundingClientRect();
        
        // Cache các giá trị cần thiết
        const wrapperWidth = wrapperRect.width;
        const contentWidth = containerRect.width;
        const maxOffsetPx = Math.max(0, contentWidth - wrapperWidth);

        // Lấy transform hiện tại một cách an toàn
        const computed = getComputedStyle(thumbnailContainer).transform;
        let currentOffsetPx = 0;
        
        if (computed && computed !== 'none') {
            const matrix = computed.match(/matrix([^)]+)/);
            if (matrix && matrix[1]) {
                const values = matrix[1].split(',').map(Number);
                if (values.length >= 6) {
                    const tx = values[4];
                    currentOffsetPx = Math.abs(tx) || 0;
                }
            }
        }

        const thumbLeft = activeThumbRect.left - containerRect.left;
        const thumbRight = thumbLeft + activeThumbRect.width;

        let newOffsetPx = currentOffsetPx;
        const padding = 6;

        if (thumbLeft < currentOffsetPx) {
            newOffsetPx = Math.max(0, thumbLeft - padding);
        } else if (thumbRight > currentOffsetPx + wrapperWidth) {
            newOffsetPx = Math.min(maxOffsetPx, thumbRight - wrapperWidth + padding);
        }

        if (newOffsetPx === currentOffsetPx) return;

        // Sử dụng transform3d để kích hoạt hardware acceleration
        requestAnimationFrameOptimized(() => {
            thumbnailContainer.style.transform = `translate3d(-${newOffsetPx}px, 0, 0)`;
            thumbnailContainer.style.willChange = 'transform';
        });
    }

    // Tối ưu hóa event listeners
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('gallery-modal')) {
            const galleryId = e.target.id.replace('modal-', '');
            window.closeModal(galleryId);
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.gallery-modal.show');
            openModals.forEach(modal => {
                const galleryId = modal.id.replace('modal-', '');
                window.closeModal(galleryId);
            });
        }
    });

    // Tối ưu hóa resize handler với debounce
    const handleResizeOptimized = debounce(function() {
        Object.keys(window.galleryData || {}).forEach(galleryId => {
            const galleryData = window.galleryData[galleryId];
            if (galleryData) {
                const screenWidth = window.innerWidth;
                let itemsPerView;
                
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
                    cache.delete(galleryId);
                    
                    // Delay để tránh forced reflow
                    requestAnimationFrameOptimized(() => {
                        adjustThumbnailPositionOptimized(galleryId, galleryData.currentIndex);
                    });
                }
            }
        });
    }, 150);

    window.addEventListener('resize', handleResizeOptimized);

    // Tối ưu hóa DOMContentLoaded handler
    document.addEventListener('DOMContentLoaded', function () {
        // Delay initial setup để tránh blocking
        requestAnimationFrameOptimized(() => {
            handleResizeOptimized();

            Object.keys(window.galleryData || {}).forEach(galleryId => {
                const container = document.getElementById(galleryId);
                if (container) {
                    const images = container.querySelectorAll('img');
                    let loadedCount = 0;
                    const totalImages = images.length;

                    if (totalImages === 0) {
                        container.classList.remove('loading');
                        return;
                    }

                    // Sử dụng Intersection Observer để lazy load
                    const imageObserver = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const img = entry.target;
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
                        });
                    }, {
                        rootMargin: '50px'
                    });

                    images.forEach(img => {
                        imageObserver.observe(img);
                    });
                }
            });
        });
    });

    // Cleanup function để giải phóng memory
    window.addEventListener('beforeunload', function() {
        cache.clear();
    });

})();