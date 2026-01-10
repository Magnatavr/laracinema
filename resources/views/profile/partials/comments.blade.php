@extends('layouts.main')

@section('title', 'Мои комментарии')

@section('content')
    <div class="profile-comments-page">
        <div class="container">
            <div class="profile-comments-header">
                <h1 class="page-title">Мои комментарии</h1>
                <a href="{{ route('main.profile.index') }}" class="btn btn--secondary">
                    <i class="fas fa-arrow-left"></i> Назад к профилю
                </a>
            </div>

            @if($comments->isNotEmpty())
                <div class="profile-comments-info">
                    <p>Всего комментариев: <strong>{{ $comments->total() }}</strong></p>
                </div>

                <div class="profile-comments-list">
                    @foreach($comments as $comment)
                        <div class="profile-comment">
                            <div class="profile-comment__header">
                                <a href="{{ route('main.movies.show', $comment->movie) }}"
                                   class="profile-comment__movie-link">
                                    <img src="{{ $comment->movie->poster ? asset('storage/' . $comment->movie->poster) : asset('assets/img/board/shrek.webp') }}"
                                         alt="{{ $comment->movie->title }}"
                                         class="profile-comment__poster">
                                    <div class="profile-comment__movie-info">
                                        <h4 class="profile-comment__movie-title">{{ $comment->movie->title }}</h4>
                                        <time class="profile-comment__date">{{ $comment->created_at->format('d.m.Y H:i') }}</time>
                                    </div>
                                </a>

                                <form action="{{ route('main.profile.comments.delete', $comment) }}"
                                      method="POST"
                                      class="profile-comment__delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="profile-comment__delete-btn"
                                            onclick="return confirm('Удалить комментарий?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="profile-comment__content">
                                @if($comment->rating)
                                    <div class="profile-comment__rating">
                                        <span class="profile-comment__rating-label">Оценка:</span>
                                        <div class="profile-comment__rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-star {{ $i * 2 <= $comment->rating ? 'fas' : 'far' }}"></i>
                                            @endfor
                                            <span class="profile-comment__rating-value">{{ $comment->rating }}/10</span>
                                        </div>
                                    </div>
                                @endif

                                <p class="profile-comment__text">{{ $comment->comment }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($comments->hasPages())
                    <div class="pagination-wrapper">
                        {{ $comments->links() }}
                    </div>
                @endif
            @else
                <div class="profile-empty">
                    <i class="fas fa-comment profile-empty__icon"></i>
                    <h3 class="profile-empty__title">У вас еще нет комментариев</h3>
                    <p class="profile-empty__text">Оставляйте комментарии к фильмам, чтобы они появились здесь</p>
                    <a href="{{ route('main.index') }}" class="btn btn--primary">
                        <i class="fas fa-film"></i> Перейти к фильмам
                    </a>
                </div>
            @endif
        </div>
    </div>


@endsection
