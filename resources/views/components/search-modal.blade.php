<div class="search-modal" data-search-modal>
    <div class="search-modal__content">
        <button class="search-modal__close" data-close-search>✕</button>

        <input
            type="text"
            class="search-modal__input"
            placeholder="Поиск фильма..."
            autocomplete="off"
            data-search-input
        >

        <div class="search-modal__results" data-search-results>
            <p class="search-modal__hint">
                Начните вводить название фильма
            </p>
        </div>

        <a href="{{ route('main.filter') }}" class="search-modal__full-link">
            Расширенный поиск →
        </a>

    </div>
</div>
