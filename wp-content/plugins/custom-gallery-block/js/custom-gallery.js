(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const galleries = document.querySelectorAll('.wp-block-gallery');

        galleries.forEach((gallery) => {
            const images = gallery.querySelectorAll('figure');
            if (images.length === 0) return;

            // Tạo cấu trúc carousel
            gallery.innerHTML = `
                <div class="custom-gallery-container">
                    <button class="prev-btn">Previous</button>
                    <div class="main-image">
                        <img src="${images[0].querySelector('img').src}" alt="Main Image">
                    </div>
                    <button class="next-btn">Next</button>
                    <div class="thumbnail-container">
                        ${Array.from(images)
                            .map(
                                (img, index) =>
                                    `<div class="thumbnail"><img src="${img.querySelector('img').src}" data-index="${index}" alt="Thumbnail"></div>`
                            )
                            .join('')}
                    </div>
                </div>
            `;

            let currentIndex = 0;

            // Thêm CSS cơ bản
            const style = document.createElement('style');
            style.textContent = `
                .custom-gallery-container {
                    position: relative;
                    margin: 0 auto;
                    text-align: center;
                }
                .main-image img {
                    width: 100%;
                    height: auto;
                    cursor: pointer;
                }
                .thumbnail-container {
                    display: flex;
                    justify-content: center;
                    gap: 10px;
                    margin-top: 10px;
                    flex-wrap: wrap;
                }
                .thumbnail {
                    position: relative;
                    flex: 0 0 auto;
                    width: 200px;
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
                    opacity: 1; /* Ảnh active hoặc hover không mờ */
                }
                .thumbnail:not(.active) img {
                    opacity: 0.5; /* Lớp phủ mờ cho ảnh không active */
                }
                .prev-btn, .next-btn {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    background: rgba(0,0,0,0.5);
                    color: white;
                    border: none;
                    padding: 10px;
                    cursor: pointer;
                    z-index: 10;
                }
                .prev-btn { left: 0; }
                .next-btn { right: 0; }
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
            const prevBtn = gallery.querySelector('.prev-btn');
            const nextBtn = gallery.querySelector('.next-btn');
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

            prevBtn.addEventListener('click', () => {
                const newIndex = (currentIndex - 1 + images.length) % images.length;
                updateMainImage(newIndex);
            });

            nextBtn.addEventListener('click', () => {
                const newIndex = (currentIndex + 1) % images.length;
                updateMainImage(newIndex);
            });

            thumbnails.forEach((thumb) => {
                thumb.addEventListener('click', () => {
                    const index = parseInt(thumb.getAttribute('data-index'));
                    updateMainImage(index);
                });
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