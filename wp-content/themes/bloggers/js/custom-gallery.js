(function () {
    'use strict';

    var cache = {};

    function debounce(func, wait) {
        var timeout;
        return function () {
            var context = this;
            var args = arguments;
            var later = function () {
                clearTimeout(timeout);
                func.apply(context, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function requestAnimationFrameOptimized(callback) {
        return requestAnimationFrame(function () {
            requestAnimationFrame(callback);
        });
    }

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

        requestAnimationFrameOptimized(function () {
            mainImg.src = currentImage.url;
            mainImg.alt = currentImage.alt;
            mainImg.setAttribute('data-index', newIndex);

            for (var i = 0; i < thumbnails.length; i++) {
                var thumb = thumbnails[i];
                if (i === newIndex) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            }

            setTimeout(function () {
                adjustThumbnailPositionOptimized(galleryId, newIndex);
            }, 0);
        });
    };

    window.openModal = function (galleryId, index) {
        var galleryData = window.galleryData && window.galleryData[galleryId];
        if (!galleryData) return;

        var modal = document.getElementById('modal-' + galleryId);
        var modalImg = modal && modal.querySelector('.modal-content-img');

        if (!modal || !modalImg) return;

        var imageIndex = typeof index === 'number' ? index : galleryData.currentIndex;
        var currentImage = galleryData.images[imageIndex];

        requestAnimationFrameOptimized(function () {
            modalImg.src = currentImage.url;
            modalImg.alt = currentImage.alt;

            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    };

    window.closeModal = function (galleryId) {
        var modal = document.getElementById('modal-' + galleryId);
        if (modal) {
            requestAnimationFrameOptimized(function () {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            });
        }
    };

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

        var wrapperWidth = wrapper.clientWidth;
        var contentWidth = thumbnailContainer.scrollWidth;
        var maxOffsetPx = Math.max(0, contentWidth - wrapperWidth);

        var thumbLeft = activeThumb.offsetLeft;
        var thumbRight = thumbLeft + activeThumb.offsetWidth;

        var computed = getComputedStyle(thumbnailContainer).transform;
        var currentOffsetPx = 0;

        if (computed && computed !== 'none') {
            var matrix = computed.match(/matrix\(([^)]+)\)/);
            if (matrix && matrix[1]) {
                var values = matrix[1].split(',').map(function (v) { return parseFloat(v.trim()); });
                if (values.length >= 6) {
                    currentOffsetPx = Math.abs(values[4]) || 0;
                }
            }
        }

        var newOffsetPx = currentOffsetPx;
        var padding = 20;

        if (thumbLeft < currentOffsetPx + padding) {
            newOffsetPx = Math.max(0, thumbLeft - padding);
        } else if (thumbRight > currentOffsetPx + wrapperWidth - padding) {
            newOffsetPx = Math.min(maxOffsetPx, thumbRight - wrapperWidth + padding);
        }

        if (newOffsetPx !== currentOffsetPx) {
            thumbnailContainer.style.transform = 'translateX(-' + newOffsetPx + 'px)';
        }
    }

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

    var handleResizeOptimized = debounce(function () {
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
                    delete cache[galleryId];

                    requestAnimationFrameOptimized(function () {
                        adjustThumbnailPositionOptimized(galleryId, galleryData.currentIndex);
                    });
                }
            }
        }
    }, 150);

    window.addEventListener('resize', handleResizeOptimized);

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

    document.addEventListener('DOMContentLoaded', function () {
        initializeGalleryData();

        requestAnimationFrameOptimized(function () {
            handleResizeOptimized();

            var galleryIds = Object.keys(window.galleryData || {});
            for (var i = 0; i < galleryIds.length; i++) {
                var galleryId = galleryIds[i];
                var container = document.getElementById(galleryId);
                if (container) {
                    container.classList.remove('loading');
                }
            }
        });
    });

    window.reinitializeGalleryData = function () {
        initializeGalleryData();
    };

    window.addEventListener('beforeunload', function () {
        cache = {};
    });

})();