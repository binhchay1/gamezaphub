(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const galleries = document.querySelectorAll('.wp-block-gallery');

        galleries.forEach((gallery) => {
            const images = gallery.querySelectorAll('figure');
            if (images.length === 0) return;

            const getImageSrc = (imgElement) => {
                const img = imgElement.querySelector('img');
                return img && img.src ? img.src : '';
            };

            gallery.innerHTML = `
                <div class="custom-gallery-container">
                    <div class="main-image">
                        <img src="${getImageSrc(images[0])}" alt="Ảnh chính">
                        <button class="zoom-btn">Phóng to</button>
                        <div class="nav-buttons">
                            <button class="prev-btn">←</button>
                            <button class="next-btn">→</button>
                        </div>
                    </div>
                    <div class="thumbnail-wrapper">
                        <div class="thumbnail-container">
                            ${Array.from(images)
                    .map(
                        (img, index) =>
                            `<div class="thumbnail"><img src="${getImageSrc(img)}" data-index="${index}" alt="Thumbnail"></div>`
                    )
                    .join('')}
                        </div>
                    </div>
                </div>
            `;

            let currentIndex = 0;
            const itemsPerView = 5;

            const style = document.createElement('style');
            style.textContent = `
                .custom-gallery-container {
                    position: relative;
                    margin: 0 auto;
                    text-align: center;
                }
                .main-image {
                    position: relative;
                    width: 100%;
                    height: 400px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .main-image img {
                    width: 100%;
                    height: 400px;
                    cursor: pointer;
                }
                .thumbnail-wrapper {
                    position: relative;
                    margin-top: 10px;
                }
                .thumbnail-container {
                    display: flex;
                    white-space: nowrap;
                    gap: 10px;
                    transition: transform 0.5s ease;
                    width: 100%;
                }
                .thumbnail {
                    flex: 0 0 ${100 / itemsPerView}%;
                    height: 100px;
                    overflow: visible;
                    min-width: ${100 / itemsPerView}%;
                }
                .thumbnail img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    cursor: pointer;
                    transition: opacity 0.3s;
                    display: block; /* Đảm bảo ảnh hiển thị */
                }
                .thumbnail.active img,
                .thumbnail:hover img {
                    opacity: 1;
                }
                .thumbnail:not(.active) img {
                    opacity: 0.5;
                }
                .nav-buttons {
                    position: absolute;
                    bottom: 10px;
                    right: 10px;
                    display: flex;
                    gap: 5px;
                }
                .prev-btn, .next-btn {
                    background: rgba(0,0,0,0.5);
                    color: white;
                    border: none;
                    padding: 10px;
                    cursor: pointer;
                    z-index: 10;
                }
                .zoom-btn {
                    position: absolute;
                    bottom: 10px;
                    left: 10px;
                    background: rgba(0,0,0,0.5);
                    color: white;
                    border: none;
                    padding: 10px;
                    cursor: pointer;
                    z-index: 10;
                }
                .modal {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.9);
                    justify-content: center;
                    align-items: center;
                    z-index: 1000;
                }
                .modal-content {
                    max-width: 90%;
                    max-height: 90%;
                }
                .close {
                    position: absolute;
                    top: 10px;
                    right: 20px;
                    color: white;
                    font-size: 30px;
                    cursor: pointer;
                }
            `;
            document.head.appendChild(style);

            const modal = document.createElement('div');
            modal.className = 'modal';
            modal.innerHTML = `
                <span class="close">×</span>
                <img class="modal-content" src="" alt="Full Screen Image">
            `;
            document.body.appendChild(modal);

            const mainImage = gallery.querySelector('.main-image img');
            const thumbnails = gallery.querySelectorAll('.thumbnail img');
            const thumbnailContainer = gallery.querySelector('.thumbnail-container');
            const zoomBtn = gallery.querySelector('.zoom-btn');
            const prevBtn = gallery.querySelector('.prev-btn');
            const nextBtn = gallery.querySelector('.next-btn');
            const closeModal = modal.querySelector('.close');
            const modalContent = modal.querySelector('.modal-content');

            function updateMainImage(index) {
                const src = getImageSrc(images[index]);
                mainImage.src = src;
                currentIndex = index;
                thumbnails.forEach((thumb, i) => {
                    const thumbnailDiv = thumb.closest('.thumbnail');
                    thumbnailDiv.classList.toggle('active', i === index);
                });
                adjustThumbnailPosition();
            }

            function adjustThumbnailPosition() {
                const thumbnailWidth = thumbnails[0].closest('.thumbnail').offsetWidth + 10;
                const maxOffset = Math.max(0, thumbnails.length - itemsPerView);

                let newOffset;
                if (thumbnails.length <= itemsPerView) {
                    newOffset = 0;
                } else if (currentIndex <= Math.floor(itemsPerView / 2)) {
                    newOffset = 0;
                } else if (currentIndex >= thumbnails.length - 1) {
                    newOffset = thumbnails.length - itemsPerView;
                } else {
                    newOffset = currentIndex - Math.floor(itemsPerView / 2);
                }

                newOffset = Math.max(0, Math.min(newOffset, maxOffset));
                thumbnailContainer.style.transform = `translateX(-${newOffset * thumbnailWidth}px)`;

                thumbnails.forEach((thumb, i) => {
                    if (i === thumbnails.length - 1) {
                        thumb.style.display = 'block';
                    }
                });
            }

            function slidePrev() {
                currentIndex = Math.max(currentIndex - 1, 0);
                updateMainImage(currentIndex);
            }

            function slideNext() {
                const maxIndex = thumbnails.length - 1;
                currentIndex = Math.min(currentIndex + 1, maxIndex);
                updateMainImage(currentIndex);
            }

            thumbnails.forEach((thumb) => {
                thumb.addEventListener('click', () => {
                    const index = parseInt(thumb.getAttribute('data-index'));
                    updateMainImage(index);
                });
            });

            prevBtn.addEventListener('click', slidePrev);
            nextBtn.addEventListener('click', slideNext);

            zoomBtn.addEventListener('click', () => {
                modal.style.display = 'flex';
                modalContent.src = mainImage.src;
            });

            closeModal.addEventListener('click', () => {
                modal.style.display = 'none';
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.style.display = 'none';
            });

            // Kiểm tra lỗi tải ảnh
            thumbnails.forEach((thumb) => {
                thumb.addEventListener('error', () => {
                    // thumb.src = 'https://via.placeholder.com/150';
                });
            });

            updateMainImage(0);
        });
    });
})();