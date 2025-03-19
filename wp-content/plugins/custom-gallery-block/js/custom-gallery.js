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
                const thumbnailWidth = thumbnails[0].closest('.thumbnail').offsetWidth + 10;
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

            updateMainImage(0);
        });
    });
})();