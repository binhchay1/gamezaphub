(function () {
    'use strict';

    window.changeImage = function (galleryId, index) {
        const galleryData = window.galleryData && window.galleryData[galleryId];
        if (!galleryData) return;

        const container = document.getElementById(galleryId);
        if (!container) return;

        const mainImg = container.querySelector('.main-gallery-image');
        const thumbnails = container.querySelectorAll('.thumbnail');

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
        mainImg.src = currentImage.url;
        mainImg.alt = currentImage.alt;
        mainImg.setAttribute('data-index', newIndex);

        thumbnails.forEach((thumb, i) => {
            thumb.classList.toggle('active', i === newIndex);
        });

        adjustThumbnailPosition(galleryId, newIndex);
    };

    window.openModal = function (galleryId, index) {
        const galleryData = window.galleryData && window.galleryData[galleryId];
        if (!galleryData) return;

        const modal = document.getElementById('modal-' + galleryId);
        const modalImg = modal.querySelector('.modal-content-img');

        if (!modal || !modalImg) return;

        const imageIndex = typeof index === 'number' ? index : galleryData.currentIndex;
        const currentImage = galleryData.images[imageIndex];

        modalImg.src = currentImage.url;
        modalImg.alt = currentImage.alt;

        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    window.closeModal = function (galleryId) {
        const modal = document.getElementById('modal-' + galleryId);
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    };

    function adjustThumbnailPosition(galleryId, currentIndex) {
        const galleryData = window.galleryData && window.galleryData[galleryId];
        if (!galleryData) return;

        const container = document.getElementById(galleryId);
        if (!container) return;

        const wrapper = container.querySelector('.thumbnail-wrapper');
        const thumbnailContainer = container.querySelector('.thumbnail-container');
        const thumbnails = container.querySelectorAll('.thumbnail');

        if (!wrapper || !thumbnailContainer || !thumbnails.length) return;

        const activeThumb = thumbnails[currentIndex];
        if (!activeThumb) return;

        const computed = getComputedStyle(thumbnailContainer).transform;
        let currentOffsetPx = 0;
        if (computed && computed !== 'none') {
            const matrix = computed.match(/matrix(([^)]+))/);
            if (matrix && matrix[1]) {
                const values = matrix[1].split(',').map(Number);
                const tx = values[4];
                currentOffsetPx = Math.abs(tx) || 0;
            }
        }

        const wrapperWidth = wrapper.clientWidth;
        const contentWidth = thumbnailContainer.scrollWidth;
        const maxOffsetPx = Math.max(0, contentWidth - wrapperWidth);

        const thumbLeft = activeThumb.offsetLeft;
        const thumbRight = thumbLeft + activeThumb.offsetWidth;

        let newOffsetPx = currentOffsetPx;
        const padding = 6;

        if (thumbLeft < currentOffsetPx) {
            newOffsetPx = Math.max(0, thumbLeft - padding);
        } else if (thumbRight > currentOffsetPx + wrapperWidth) {
            newOffsetPx = Math.min(maxOffsetPx, thumbRight - wrapperWidth + padding);
        }

        if (newOffsetPx === currentOffsetPx) return;

        thumbnailContainer.style.transform = `translateX(-${newOffsetPx}px)`;
    }

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

    function handleResize() {
        Object.keys(window.galleryData || {}).forEach(galleryId => {
            const galleryData = window.galleryData[galleryId];
            if (galleryData) {
                const screenWidth = window.innerWidth;
                if (screenWidth <= 480) {
                    galleryData.itemsPerView = 3;
                } else if (screenWidth <= 768) {
                    galleryData.itemsPerView = 4;
                } else {
                    galleryData.itemsPerView = 5;
                }

                adjustThumbnailPosition(galleryId, galleryData.currentIndex);
            }
        });
    }

    let resizeTimeout;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(handleResize, 250);
    });

    document.addEventListener('DOMContentLoaded', function () {
        handleResize();

        Object.keys(window.galleryData || {}).forEach(galleryId => {
            const container = document.getElementById(galleryId);
            if (container) {
                const images = container.querySelectorAll('img');
                let loadedCount = 0;
                const totalImages = images.length;

                images.forEach(img => {
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
                });

                if (loadedCount === totalImages) {
                    container.classList.remove('loading');
                }
            }
        });
    });

})();