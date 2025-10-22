/**
 * Custom JavaScript for Bloggers Theme
 *
 * PHẦN 1: Logic Gallery trên trang (Code gốc của bro, đã xóa open/close modal cũ)
 * PHẦN 2: Logic Video Player (Code gốc của bro)
 * PHẦN 3: Logic Modal DUY NHẤT (Code mới)
 */

// ==========================================================================
// PHẦN 1: Custom Gallery - Performance Enhanced Version
// (Đây là code gốc của bro, giữ nguyên phần performance tối ưu)
// ** ĐÃ XÓA window.openModal và window.closeModal KHỎI ĐÂY **
// ==========================================================================
(function () {
    'use strict';

    const cache = {
        elements: {},
        geometry: {},
        pendingWrites: new Map()
    };

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

    function requestAnimationFrameOptimized(callback) {
        return requestAnimationFrame(() => {
            requestAnimationFrame(callback);
        });
    }

    function batchDOMWrites(galleryId, writeOperations) {
        if (!cache.pendingWrites.has(galleryId)) {
            cache.pendingWrites.set(galleryId, []);
        }

        cache.pendingWrites.get(galleryId).push(...writeOperations);

        requestAnimationFrameOptimized(() => {
            const writes = cache.pendingWrites.get(galleryId) || [];
            writes.forEach(operation => operation());
            cache.pendingWrites.delete(galleryId);
        });
    }

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

    function cacheGeometryValues(galleryId) {
        const elements = getCachedElements(galleryId);
        if (!elements) return null;

        const { wrapper, thumbnailContainer, thumbnails } = elements;

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

    function adjustThumbnailPositionOptimized(galleryId, currentIndex) {
        const galleryData = window.galleryData?.[galleryId];
        if (!galleryData) return;

        const elements = getCachedElements(galleryId);
        if (!elements) return;

        const { thumbnailContainer } = elements;
        if (!thumbnailContainer) return;

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
        let currentIndex = galleryData.currentIndex;

        if (typeof index === 'number') {
            if (index === -1) { // Nút Previous
                newIndex = Math.max(0, currentIndex - 1);
            } else { // Click thumbnail
                newIndex = Math.max(0, Math.min(index, galleryData.images.length - 1));
            }
        } else if (index === 'next') { // Nút Next
            newIndex = Math.min(galleryData.images.length - 1, currentIndex + 1);
        } else {
            newIndex = currentIndex;
        }

        // Cập nhật lại data-gallery-current trên DOM
        const container = document.getElementById(galleryId);
        if (container) {
            container.setAttribute('data-gallery-current', newIndex);
        }

        galleryData.currentIndex = newIndex;
        const currentImage = galleryData.images[newIndex];

        const writeOperations = [
            () => {
                mainImg.src = currentImage.url;
                mainImg.alt = currentImage.alt;
                mainImg.setAttribute('data-index', newIndex);
            },
            () => {
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

        delete cache.geometry[galleryId];

        requestAnimationFrameOptimized(() => {
            adjustThumbnailPositionOptimized(galleryId, newIndex);
        });
    };

    // ** window.openModal VÀ window.closeModal đã bị xóa khỏi đây **
    // ** Các event listener cho modal cũ cũng đã bị xóa **

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

})();

// ==========================================================================
// PHẦN 2: Video Player (Code gốc của bro)
// ==========================================================================
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

        if (!video) return; // Bảo vệ code

        video.addEventListener('loadedmetadata', () => {
            duration.textContent = formatTime(video.duration);
            progressBar.max = parseInt(video.duration, 10);
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
            if (video.seeking) return; // Không cập nhật nếu đang tua
            progressBar.value = video.currentTime;
            currentTime.textContent = formatTime(video.currentTime);
            requestAnimationFrame(updateProgress);
        }

        video.addEventListener('play', () => {
            requestAnimationFrame(updateProgress);
        });

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


// ==========================================================================
// PHẦN 3: Logic Modal DUY NHẤT (Code mới)
// ==========================================================================
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('site-wide-gallery-modal');
        if (!modal) return; // Không làm gì nếu không có modal

        const modalImg = modal.querySelector('.modal-content-img');
        const closeBtn = modal.querySelector('#site-wide-modal-close');
        const prevBtn = modal.querySelector('.modal-nav.modal-prev');
        const nextBtn = modal.querySelector('.modal-nav.modal-next');

        let currentGalleryId = null;
        let currentIndex = 0;

        /**
         * Mở modal và hiển thị ảnh
         */
        function openSiteModal(galleryId, index) {
            const galleryData = window.galleryData?.[galleryId];
            if (!galleryData) return;

            currentGalleryId = galleryId;
            currentIndex = index;

            const image = galleryData.images[currentIndex];
            if (!image) return;

            // Tải ảnh trước khi hiển thị
            modalImg.src = image.url;
            modalImg.alt = image.alt;

            modalImg.onload = () => {
                // Cập nhật trạng thái nút
                prevBtn.disabled = (currentIndex === 0);
                nextBtn.disabled = (currentIndex === galleryData.images.length - 1);

                // Hiển thị modal
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                requestAnimationFrame(() => {
                    modal.classList.add('show');
                });
            };
            modalImg.onerror = () => {
                console.error("Không thể tải ảnh: ", image.url);
                closeSiteModal(); // Đóng modal nếu ảnh lỗi
            }
        }

        /**
         * Đóng modal
         */
        function closeSiteModal() {
            modal.classList.remove('show');
            document.body.style.overflow = '';

            // Chờ animation chạy xong mới display: none
            modal.addEventListener('transitionend', () => {
                if (!modal.classList.contains('show')) {
                    modal.style.display = 'none';
                    modalImg.src = ''; // Xóa src để dừng tải (nếu có)
                }
            }, { once: true });

            // Fallback nếu transition không chạy
            setTimeout(() => {
                if (!modal.classList.contains('show')) {
                    modal.style.display = 'none';
                    modalImg.src = '';
                }
            }, 350);
        }

        /**
         * Chuyển ảnh (prev/next) trong modal
         */
        function changeModalImage(direction) {
            const galleryData = window.galleryData?.[currentGalleryId];
            if (!galleryData) return;

            let newIndex = currentIndex + direction;

            // Đảm bảo index nằm trong giới hạn
            if (newIndex >= 0 && newIndex < galleryData.images.length) {
                openSiteModal(currentGalleryId, newIndex); // Gọi lại openModal để cập nhật
            }
        }

        // DÙNG EVENT DELEGATION ĐỂ XỬ LÝ CLICK
        document.addEventListener('click', function (e) {

            // 1. Click nút "Phóng to"
            // dùng .closest() để bắt cả icon svg bên trong
            const zoomBtn = e.target.closest('.zoom-btn');
            if (zoomBtn) {
                e.preventDefault();
                const galleryId = zoomBtn.dataset.galleryId;
                const galleryData = window.galleryData?.[galleryId];
                if (galleryData) {
                    // SỬA LỖI: Lấy currentIndex (ảnh đang xem) thay vì 0
                    openSiteModal(galleryId, galleryData.currentIndex);
                }
            }

            // 2. Click nút Close (X)
            if (e.target === closeBtn) {
                e.preventDefault();
                closeSiteModal();
            }

            // 3. Click vào nền mờ (bên ngoài ảnh)
            if (e.target === modal) {
                closeSiteModal();
            }

            // 4. Click nút Prev/Next trong modal
            if (e.target === prevBtn) {
                changeModalImage(-1); // Lùi
            }
            if (e.target === nextBtn) {
                changeModalImage(1); // Tới
            }
        });

        // 5. Đóng modal bằng nút Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('show')) {
                closeSiteModal();
            }
        });
    });
})();