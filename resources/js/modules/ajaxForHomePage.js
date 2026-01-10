

export default function AjaxForHomePage() {
    // Обработчик для hero пагинации
    document.addEventListener('click', function(e) {


        // Проверяем, кликнули ли на ссылку hero пагинации
        const heroLink = e.target.closest('.hero-pagination-link');

        if (heroLink) {
            e.preventDefault();

            const page = heroLink.dataset.page;
            const type = heroLink.dataset.type; // Должно быть 'hero'


            const heroContainer = document.querySelector('.hero__container');




            fetch(`/?paginate=1&type=${type}&page=${page}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {

                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {

                    if (data.html) {
                        // Заменяем контент hero
                        heroContainer.outerHTML = data.html;

                        // Обновляем URL в адресной строке
                        history.pushState(null, '', `#hero`);


                    } else {
                        console.error('No HTML in response');
                        heroContainer.innerHTML = originalContent;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Восстанавливаем оригинальный контент
                    heroContainer.innerHTML = originalContent;
                    alert('Ошибка загрузки. Попробуйте снова.');
                });
        }

        // Проверяем, кликнули ли на ссылку пагинации фильмов
        const paginationLink = e.target.closest('.pagination-link');
        if (paginationLink) {
            e.preventDefault();

            const type = paginationLink.dataset.type; // 'recent' или 'popular'
            const page = paginationLink.dataset.page;

            const sectionGrid = document.getElementById(`${type}-movies-grid`);

            if (!sectionGrid) {
                console.error('Section grid not found for type:', type);
                return;
            }

            const originalContent = sectionGrid.innerHTML;

            // AJAX запрос для фильмов
            fetch(`/?paginate=1&type=${type}&page=${page}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.html) {
                        // Заменяем контент
                        sectionGrid.innerHTML = data.html;

                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Восстанавливаем оригинальный контент
                    sectionGrid.innerHTML = originalContent;
                    alert('Ошибка загрузки. Попробуйте снова.');
                });
        }
    });
}


