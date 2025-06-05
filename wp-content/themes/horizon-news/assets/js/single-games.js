var currentIndex = 0;
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
                    <span class="close">×</span>
                    <img class="modal-content" src="" alt="Full Screen Image">
                `;
    document.body.appendChild(modal);

    const gallery = document.querySelector('.custom-gallery-container');
    const mainImage = gallery.querySelector('.main-image img');
    const thumbnails = gallery.querySelectorAll('.thumbnail img');
    const zoomBtn = gallery.querySelector('.zoom-btn');
    const closeModal = modal.querySelector('.close');
    const modalContent = modal.querySelector('.modal-content');

    function updateMainImage(index) {
        mainImage.src = thumbnails[index].src;
        currentIndex = index;
        thumbnails.forEach((thumb, i) => {
            const thumbnailDiv = thumb.closest('.thumbnail');
            thumbnailDiv.classList.toggle('active', i === index);
        });
    }

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

    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) modal.style.display = 'none';
    });

    updateMainImage(0);
});
