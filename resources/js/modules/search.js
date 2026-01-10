export default function initSearch() {
    const modal = document.querySelector('[data-search-modal]');
    const input = document.querySelector('[data-search-input]');
    const results = document.querySelector('[data-search-results]');
    const btnSearch = document.querySelector('[data-open-search]');
    const btnClose = document.querySelector('[data-close-search]');

    if (!modal || !input || !results || !btnSearch) return;

    let timer = null;

    // Открытие модалки
    btnSearch.addEventListener('click', () => {

        modal.classList.add('active');
        input.focus();
    });

    // Закрытие по кнопке закрытия
    if (btnClose) {
        btnClose.addEventListener('click', closeModal);
    }

    // Закрытие по клику на оверлей (саму модалку)
    modal.addEventListener('click', (e) => {
        // Закрываем только если кликнули именно на саму модалку (а не на её содержимое)
        if (e.target === modal) {
            closeModal();
        }
    });

    // Закрытие по клавише Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // Функция закрытия модалки
    function closeModal() {

        modal.classList.remove('active');
        input.value = '';
        results.innerHTML = '<p class="search-modal__hint">Начните вводить название фильма</p>';
    }

    // Поиск при вводе
    input.addEventListener('input', (e) => {
        clearTimeout(timer);

        const query = input.value.trim();


        // Если меньше 2 символов - показываем подсказку
        if (query.length < 2) {
            results.innerHTML = '<p class="search-modal__hint">Введите минимум 2 символа</p>';
            return;
        }

        // Показываем загрузку
        results.innerHTML = '<p class="search-modal__hint">Идет поиск...</p>';

        timer = setTimeout(async () => {
            try {
                const res = await fetch(`/api/search?query=${encodeURIComponent(query)}`);

                if (!res.ok) {
                    throw new Error(`Ошибка HTTP: ${res.status}`);
                }

                const data = await res.json();

                if (!data.length) {
                    results.innerHTML = '<p class="search-modal__hint">Ничего не найдено</p>';
                    return;
                }

                results.innerHTML = data.map(movie => `
                    <a href="movies/${movie.id}" class="search-modal__item">
                        <img src="${movie.poster ? '/storage/' + movie.poster : '/assets/img/board/shrek.webp'}" alt="${movie.title}">
                        <div class="search-modal__item-title">${movie.title}</div>
                    </a>
                `).join('');

            } catch (error) {
                console.error('Ошибка поиска:', error);
                results.innerHTML = '<p class="search-modal__hint">Ошибка при поиске. Попробуйте позже.</p>';
            }
        }, 300);
    });
}
