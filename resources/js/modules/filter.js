export default function filter() {
    // Обновление значения рейтинга при движении слайдера
    const ratingSlider = document.getElementById('rating-slider');
    const ratingValue = document.getElementById('rating-value');

    if (ratingSlider && ratingValue) {
        ratingSlider.addEventListener('input', function () {
            ratingValue.textContent = this.value;
        });
    }

    // AJAX отправка формы (опционально)
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function (e) {
            // Можно добавить AJAX отправку здесь
            // Но пока оставим обычную отправку формы
        });
    }

    // Авто-отправка при изменении некоторых полей
    const autoSubmitFields = document.querySelectorAll('.rating-slider__input, .filter-select');
    autoSubmitFields.forEach(field => {
        field.addEventListener('change', function () {
            filterForm.submit();
        });
    });
}
