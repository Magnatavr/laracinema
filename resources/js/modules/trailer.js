
export default function modalTrailer(){
    const trailerModal = document.getElementById('trailerModal');
    const openTrailerBtn = document.querySelector('[data-open-trailer]');

    if (!trailerModal || !openTrailerBtn) return;

    const closeTrailerBtns = document.querySelectorAll('[data-close-trailer]');
    const trailerVideo = trailerModal.querySelector('.movie-trailer-modal__video');

    // Открытие модалки
    openTrailerBtn.addEventListener('click', () => {
        trailerModal.classList.add('active');
        document.body.style.overflow = 'hidden';

        if (trailerVideo) {
            // Добавляем небольшой таймаут для лучшего UX
            setTimeout(() => {
                trailerVideo.play().catch(e => {
                    console.log('Автовоспроизведение заблокировано:', e);
                });
            }, 300);
        }
    });

    // Закрытие модалки
    closeTrailerBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            trailerModal.classList.remove('active');
            document.body.style.overflow = '';

            if (trailerVideo) {
                trailerVideo.pause();
                trailerVideo.currentTime = 0;
            }
        });
    });

    // Закрытие по Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && trailerModal.classList.contains('active')) {
            trailerModal.classList.remove('active');
            document.body.style.overflow = '';

            if (trailerVideo) {
                trailerVideo.pause();
                trailerVideo.currentTime = 0;
            }
        }
    });

    // Плавный скролл к плееру
    const watchBtn = document.querySelector('.movie-single__watch-btn');
    if (watchBtn && watchBtn.hash === '#player') {
        watchBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const playerSection = document.getElementById('player');
            if (playerSection) {
                window.scrollTo({
                    top: playerSection.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    }
}


