(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const galleries = document.querySelectorAll('.wp-block-gallery');

        galleries.forEach((gallery) => {
            const images = gallery.querySelectorAll('figure');
            if (images.length === 0) return;

            gallery.innerHTML = `
                <div class="custom-gallery-container">
                    <div class="main-image">
                        <img src="${images[0].querySelector('img').src}" alt="Main Image">
                        <button class="zoom-btn">Zoom</button>
                    </div>
                    <div class="thumbnail-container">
                        ${Array.from(images)
                            .map(
                                (img, index) =>
                                    `<div class="thumbnail"><img src="${img.querySelector('img').src}" data-index="${index}" alt="Thumbnail"></div>`
                            )
                            .join('')}
                    </div>
                    <button class="next-btn">Next</button>
                </div>
            `;

            let currentIndex = 0;
            const itemsPerView = 4;

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
                }
                .main-image img {
                    width: 100%;
                    height: auto;
                    cursor: pointer;
                }
                .thumbnail-container {
                    display: flex;
                    overflow-x: hidden; /* Ẩn phần thừa để tạo carousel */
                    white-space: nowrap;
                    gap: 10px;
                    margin-top: 10px;
                    transition: transform 0.5s ease; /* Hiệu ứng chuyển động mượt */
                }
                .thumbnail {
                    flex: 0 0 ${100 / itemsPerView}%; /* Chia đều 4 ảnh trong 100% chiều rộng */
                    height: 100px;
                    overflow: hidden;
                }
                .thumbnail img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    cursor: pointer;
                    transition: opacity 0.3s;
                }
                .thumbnail.active img,
                .thumbnail:hover img {
                    opacity: 1;
                }
                .thumbnail:not(.active) img {
                    opacity: 0.5;
                }
                .next-btn {
                    position: absolute;
                    bottom: 10px;
                    right: 10px;
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

            // Thêm modal phóng to
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
            const nextBtn = gallery.querySelector('.next-btn');
            const zoomBtn = gallery.querySelector('.zoom-btn');
            const closeModal = modal.querySelector('.close');
            const modalContent = modal.querySelector('.modal-content');

            function updateMainImage(index) {
                mainImage.src = images[index].querySelector('img').src;
                currentIndex = index;
                thumbnails.forEach((thumb, i) => {
                    const thumbnailDiv = thumb.closest('.thumbnail');
                    thumbnailDiv.classList.toggle('active', i === index);
                });
            }

            function slideThumbnails() {
                const thumbnailWidth = thumbnails[0].closest('.thumbnail').offsetWidth + 10; // Chiều rộng + gap
                const maxOffset = Math.max(0, thumbnails.length - itemsPerView);
                const newIndex = Math.min(currentIndex + 1, maxOffset);
                thumbnailContainer.style.transform = `translateX(-${newIndex * thumbnailWidth}px)`;
                currentIndex = newIndex;
                updateMainImage(currentIndex);
            }

            nextBtn.addEventListener('click', () => {
                slideThumbnails();
            });

            thumbnails.forEach((thumb) => {
                thumb.addEventListener('click', () => {
                    const index = parseInt(thumb.getAttribute('data-index'));
                    updateMainImage(index);
                });
            });

            zoomBtn.addEventListener('click', () => {
                modal.style.display = 'flex';
                modalContent.src = mainImage.src;
            });

            mainImage.addEventListener('click', () => {
                modal.style.display = 'flex';
                modalContent.src = mainImage.src;
            });

            closeModal.addEventListener('click', () => {
                modal.style.display = 'none';
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.style.display = 'none';
            });

            // Khởi tạo
            updateMainImage(0);
        });
    });
})();