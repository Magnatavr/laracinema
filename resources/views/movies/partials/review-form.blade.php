<section class="review">
    <form class="comment-form" action="{{ route('main.profile.comments.store') }}" method="POST">
        @csrf

        <div class="review__header">
            <h2>Оставить отзыв</h2>
            <p>Поделитесь вашим мнением о фильме</p>
        </div>

        <div class="review__group">
            <label class="review__label">Оценка <span class="required">*</span></label>
            <div class="rating-container">
                <select class="review__select" name="rating" required>
                    <option value="" disabled selected>Выберите оценку</option>
                    @for($i = 10; $i >= 1; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                <div class="rating-preview">
                    <span class="rating-value">0</span>/10
                </div>
            </div>
        </div>

        <div class="review__group">
            <label class="review__label">Комментарий <span class="required">*</span></label>
            <textarea class="review__textarea" name="comment" rows="5" required
                      placeholder="Напишите ваш отзыв здесь..."></textarea>

        </div>

        <input type="hidden" name="movie_id" value="{{ $movie->id }}">

        <div class="review__actions">
            <button type="submit" class="review__submit">
                <span>Отправить отзыв</span>
                <svg class="submit-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M22 2L11 13M22 2L15 22L11 13M22 2L2 9L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <button type="reset" class="review__reset">Очистить форму</button>
        </div>

        <div class="form-notice">
            <span class="required">*</span> — обязательные поля для заполнения
        </div>
    </form>
</section>
