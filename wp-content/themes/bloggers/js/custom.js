/**
 * Custom JavaScript for Bloggers Theme
 * Combined functionality for Owl Carousel and Custom Gallery
 */

// ==========================================================================
// Custom Gallery - Performance Enhanced Version
// ==========================================================================
/**
 * Optimized Custom Gallery - Performance Enhanced Version
 * 
 * Key Performance Improvements:
 * 1. Separated DOM reads and writes using requestAnimationFrame batching
 * 2. Cached geometry values to minimize repeated calculations
 * 3. Eliminated forced reflows by batching style changes
 * 4. Fixed image click and slide navigation functionality
 * 5. Maintained clean ES6 module-style structure
 */

(function () {
    'use strict';

    // Performance optimization: Cache for DOM elements and geometry data
    const cache = {
        elements: {},
        geometry: {},
        pendingWrites: new Map()
    };

    /**
     * Debounce utility for resize events
     */
    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            const context = this;
            const later = () => {
                clearTimeout(timeout);
                func.apply(context, args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Double RAF for optimal timing - ensures DOM updates are complete
     */
    function requestAnimationFrameOptimized(callback) {
        return requestAnimationFrame(() => {
            requestAnimationFrame(callback);
        });
    }

    /**
     * Batch DOM writes to prevent forced reflows
     */
    function batchDOMWrites(galleryId, writeOperations) {
        if (!cache.pendingWrites.has(galleryId)) {
            cache.pendingWrites.set(galleryId, []);
        }

        cache.pendingWrites.get(galleryId).push(...writeOperations);

        // Process all pending writes in the next frame
        requestAnimationFrameOptimized(() => {
            const writes = cache.pendingWrites.get(galleryId) || [];
            writes.forEach(operation => operation());
            cache.pendingWrites.delete(galleryId);
        });
    }

    /**
     * Get cached DOM elements for a gallery
     */
    function getCachedElements(galleryId) {
        if (!cache.elements[galleryId]) {
            const container = document.getElementById(galleryId);
            if (!container) return null;

            cache.elements[galleryId] = {
                container,
                mainImg: container.querySelector('.main-gallery-image'),
                thumbnails: container.querySelectorAll('.thumbnail'),
                wrapper: container.querySelector('.thumbnail-wrapper'),
                thumbnailContainer: container.querySelector('.thumbnail-container')
            };
        }
        return cache.elements[galleryId];
    }

    /**
     * Cache geometry values to avoid repeated DOM reads
     */
    function cacheGeometryValues(galleryId) {
        const elements = getCachedElements(galleryId);
        if (!elements) return null;

        const { wrapper, thumbnailContainer, thumbnails } = elements;

        // Batch all DOM reads together to prevent forced reflows
        const geometry = {
            wrapperWidth: wrapper.clientWidth,
            contentWidth: thumbnailContainer.scrollWidth,
            thumbnails: Array.from(thumbnails).map(thumb => ({
                element: thumb,
                offsetLeft: thumb.offsetLeft,
                offsetWidth: thumb.offsetWidth
            }))
        };

        cache.geometry[galleryId] = geometry;
        return geometry;
    }

    /**
     * Optimized thumbnail position adjustment - eliminates forced reflows
     */
    function adjustThumbnailPositionOptimized(galleryId, currentIndex) {
        const galleryData = window.galleryData?.[galleryId];
        if (!galleryData) return;

        const elements = getCachedElements(galleryId);
        if (!elements) return;

        const { thumbnailContainer } = elements;
        if (!thumbnailContainer) return;

        // Get cached geometry or calculate if not available
        let geometry = cache.geometry[galleryId];
        if (!geometry) {
            geometry = cacheGeometryValues(galleryId);
            if (!geometry) return;
        }

        const { wrapperWidth, contentWidth, thumbnails } = geometry;
        const maxOffsetPx = Math.max(0, contentWidth - wrapperWidth);

        const activeThumb = thumbnails[currentIndex];
        if (!activeThumb) return;

        const { offsetLeft: thumbLeft, offsetWidth } = activeThumb;
        const thumbRight = thumbLeft + offsetWidth;

        // Get current transform without triggering reflow
        const computed = getComputedStyle(thumbnailContainer).transform;
        let currentOffsetPx = 0;

        if (computed && computed !== 'none') {
            const matrix = computed.match(/matrix\(([^)]+)\)/);
            if (matrix?.[1]) {
                const values = matrix[1].split(',').map(v => parseFloat(v.trim()));
                if (values.length >= 6) {
                    currentOffsetPx = Math.abs(values[4]) || 0;
                }
            }
        }

        let newOffsetPx = currentOffsetPx;
        const padding = 20;

        if (thumbLeft < currentOffsetPx + padding) {
            newOffsetPx = Math.max(0, thumbLeft - padding);
        } else if (thumbRight > currentOffsetPx + wrapperWidth - padding) {
            newOffsetPx = Math.min(maxOffsetPx, thumbRight - wrapperWidth + padding);
        }

        // Only apply transform if position actually changed
        if (newOffsetPx !== currentOffsetPx) {
            batchDOMWrites(galleryId, [
                () => {
                    thumbnailContainer.style.transform = `translateX(-${newOffsetPx}px)`;
                }
            ]);
        }
    }

    window.changeImage = function (galleryId, index) {
        const galleryData = window.galleryData?.[galleryId];
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

        // Batch all DOM writes together
        const writeOperations = [
            () => {
                mainImg.src = currentImage.url;
                mainImg.alt = currentImage.alt;
                mainImg.setAttribute('data-index', newIndex);
            },
            () => {
                // Update thumbnail active states
                thumbnails.forEach((thumb, i) => {
                    if (i === newIndex) {
                        thumb.classList.add('active');
                    } else {
                        thumb.classList.remove('active');
                    }
                });
            }
        ];

        batchDOMWrites(galleryId, writeOperations);

        // Clear geometry cache and recalculate after DOM updates
        delete cache.geometry[galleryId];

        // Adjust thumbnail position after a short delay to ensure DOM is updated
        requestAnimationFrameOptimized(() => {
            adjustThumbnailPositionOptimized(galleryId, newIndex);
        });
    };

    /**
     * Public API: Open modal with optimized performance
     */
    window.openModal = function (galleryId, index) {
        const galleryData = window.galleryData?.[galleryId];
        if (!galleryData) return;

        const modal = document.getElementById(`modal-${galleryId}`);
        const modalImg = modal?.querySelector('.modal-content-img');

        if (!modal || !modalImg) return;

        const imageIndex = typeof index === 'number' ? index : galleryData.currentIndex;
        const currentImage = galleryData.images[imageIndex];

        // Batch modal operations
        const writeOperations = [
            () => {
                modalImg.src = currentImage.url;
                modalImg.alt = currentImage.alt;
            },
            () => {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        ];

        batchDOMWrites(galleryId, writeOperations);
    };

    /**
     * Public API: Close modal with optimized performance
     */
    window.closeModal = function (galleryId) {
        const modal = document.getElementById(`modal-${galleryId}`);
        if (!modal) return;

        const writeOperations = [
            () => {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        ];

        batchDOMWrites(galleryId, writeOperations);
    };

    /**
     * Event listeners for modal interactions
     */
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

    /**
     * Optimized resize handler with geometry cache invalidation
     * Uses global optimizedResize event from performance-optimizer.js
     */
    const handleResizeOptimized = function (event) {
        const galleryIds = Object.keys(window.galleryData || {});
        const screenWidth = event?.detail?.width || window.innerWidth;

        galleryIds.forEach(galleryId => {
            const galleryData = window.galleryData[galleryId];
            if (!galleryData) return;

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

                delete cache.elements[galleryId];
                delete cache.geometry[galleryId];

                requestAnimationFrameOptimized(() => {
                    adjustThumbnailPositionOptimized(galleryId, galleryData.currentIndex);
                });
            }
        });
    };

    // Use optimizedResize event if available, fallback to regular resize
    if (window.PerformanceOptimizer) {
        window.addEventListener('optimizedResize', handleResizeOptimized);
    } else {
        window.addEventListener('resize', debounce(handleResizeOptimized, 150));
    }

    function initializeGalleryData() {
        const galleries = document.querySelectorAll('.custom-gallery-container[data-gallery-images]');
        window.galleryData = window.galleryData || {};

        galleries.forEach(container => {
            const galleryId = container.getAttribute('data-gallery-id');

            if (galleryId && !window.galleryData[galleryId]) {
                try {
                    const images = JSON.parse(container.getAttribute('data-gallery-images'));
                    const currentIndex = parseInt(container.getAttribute('data-gallery-current')) || 0;
                    const itemsPerView = parseInt(container.getAttribute('data-gallery-items-per-view')) || 5;

                    window.galleryData[galleryId] = {
                        images,
                        currentIndex,
                        itemsPerView
                    };
                } catch (e) {
                    console.warn('Error parsing gallery data for', galleryId, e);
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initializeGalleryData();

        requestAnimationFrameOptimized(() => {
            handleResizeOptimized();

            const galleryIds = Object.keys(window.galleryData || {});
            galleryIds.forEach(galleryId => {
                const container = document.getElementById(galleryId);
                if (container) {
                    container.classList.remove('loading');
                }
            });
        });
    });

    window.reinitializeGalleryData = function () {
        initializeGalleryData();
    };

    window.addEventListener('beforeunload', function () {
        cache.elements = {};
        cache.geometry = {};
        cache.pendingWrites.clear();
    });

    document.addEventListener('DOMContentLoaded', () => {
        const videoPlayers = document.querySelectorAll('.video-player');

        videoPlayers.forEach(player => {
            const video = player.querySelector('.video');
            const playPauseBtn = player.querySelector('.play-pause');
            const volumeBtn = player.querySelector('.volume-btn');
            const volumeBar = player.querySelector('.volume-bar');
            const progressBar = player.querySelector('.progress-bar');
            const currentTime = player.querySelector('.current-time');
            const duration = player.querySelector('.duration');
            const nowPlaying = player.querySelector('.now-playing');

            video.addEventListener('loadedmetadata', () => {
                duration.textContent = formatTime(video.duration);
                progressBar.max = parseInt(video.duration);
            });

            playPauseBtn.addEventListener('click', () => {
                if (video.paused) {
                    video.play();
                    playPauseBtn.textContent = '⏸';
                    nowPlaying.style.display = 'block';
                    setTimeout(() => {
                        nowPlaying.style.display = 'none';
                    }, 1000);
                } else {
                    video.pause();
                    playPauseBtn.textContent = '▶';
                }
            });

            volumeBtn.addEventListener('click', () => {
                if (video.muted) {
                    video.muted = false;
                    video.volume = volumeBar.value;
                    updateVolumeIcon(video.volume);
                } else {
                    video.muted = true;
                    volumeBtn.textContent = '🔇';
                }
            });

            volumeBar.addEventListener('input', () => {
                video.volume = volumeBar.value;
                video.muted = false;
                updateVolumeIcon(video.volume);
            });

            function updateProgress() {
                if (!video.paused && !video.ended) {
                    // Use scheduler if available to batch DOM writes
                    if (window.performanceScheduler) {
                        window.performanceScheduler.mutate(() => {
                            progressBar.value = video.currentTime;
                            currentTime.textContent = formatTime(video.currentTime);
                        });
                    } else {
                        progressBar.value = video.currentTime;
                        currentTime.textContent = formatTime(video.currentTime);
                    }
                }
                requestAnimationFrame(updateProgress);
            }
            requestAnimationFrame(updateProgress);

            video.addEventListener('ended', () => {
                progressBar.value = progressBar.max;
                currentTime.textContent = formatTime(video.duration);
                playPauseBtn.textContent = '⟲';
            });

            progressBar.addEventListener('input', () => {
                video.currentTime = progressBar.value;
            });

            function updateVolumeIcon(volume) {
                if (volume == 0 || video.muted) {
                    volumeBtn.textContent = '🔇';
                } else if (volume < 0.5) {
                    volumeBtn.textContent = '🔉';
                } else {
                    volumeBtn.textContent = '🔊';
                }
            }

            function formatTime(time) {
                const minutes = Math.floor(time / 60);
                const seconds = Math.floor(time % 60);
                return `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            }
        });
    });

})();
