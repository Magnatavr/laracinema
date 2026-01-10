
    export default function likesToggle(){

    // Обработка лайков
    document.addEventListener('click', async function(e) {
        if (e.target.closest('.like-btn')) {
            e.preventDefault();

            const button = e.target.closest('.like-btn');
            const url = button.dataset.likeUrl;
            const isLiked = button.dataset.isLiked === 'true';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Обновляем иконку
                    const icon = button.querySelector('i');
                    const countSpan = button.querySelector('.like-count');

                    if (data.is_liked) {
                        icon.className = 'fas fa-heart';
                        button.style.color = '#e50914';
                        button.dataset.isLiked = 'true';
                    } else {
                        icon.className = 'far fa-heart';
                        button.style.color = '';
                        button.dataset.isLiked = 'false';
                    }

                    // Обновляем счетчик
                    if (countSpan) {
                        countSpan.textContent = data.likes_count;
                    }

                    // Показываем уведомление
                    showNotification(data.message, 'success');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Ошибка при обработке лайка', 'error');
            }
        }
    });}


function showNotification(message, type = 'info') {
    // Создаем элемент уведомления
    const notification = document.createElement('div');
    notification.className = `notification notification--${type}`;
    notification.textContent = message;

    // Добавляем на страницу
    document.body.appendChild(notification);

    // Показываем
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);

    // Убираем через 3 секунды
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
