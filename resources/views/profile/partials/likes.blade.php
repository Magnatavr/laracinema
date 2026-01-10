@extends('layouts.main')

@section('title', 'Мои лайки')

@section('content')
    <div class="profile-likes-page">
        <div class="container">
            <div class="profile-likes-header">
                <h1 class="page-title">Мои лайки</h1>
                <a href="{{ route('main.profile.index') }}" class="btn btn--secondary">
                    <i class="fas fa-arrow-left"></i> Назад к профилю
                </a>
            </div>

            @if($movies->isNotEmpty())
                <div class="profile-likes-info">
                    <p>Всего лайков: <strong>{{ $movies->total() }}</strong></p>
                </div>

                <div class="movies-grid">
                    @foreach($movies as $movie)
                        @include('components.movie-card', [
                            'movie' => $movie,
                            'showLikeButton' => true
                        ])
                    @endforeach
                </div>

                @if($movies->hasPages())
                    <div class="pagination-wrapper">
                        {{ $movies->links() }}
                    </div>
                @endif
            @else
                <div class="profile-empty">
                    <i class="fas fa-heart profile-empty__icon"></i>
                    <h3 class="profile-empty__title">У вас еще нет лайков</h3>
                    <p class="profile-empty__text">Ставьте лайки фильмам, чтобы они появились здесь</p>
                    <a href="{{ route('main.index') }}" class="btn btn--primary">
                        <i class="fas fa-film"></i> Перейти к фильмам
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
