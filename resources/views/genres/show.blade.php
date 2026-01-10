@extends('layouts.main')

@section('title', "Фильмы в жанре {$genre->name}")

@section('content')
    <div class="genre-movies-page mt-5">
        <div class="container">
            <div class="genre-header">
                <h1 class="page-title">Жанр: {{ ucfirst($genre->name) }}</h1>
                <div class="genre-count">Найдено фильмов: {{ $movies->total() }}</div>
            </div>

            {{-- Список фильмов --}}
            <div class="movies-flex">

                    @include('components.movie-card', ['movies' => $movies])

            </div>

            {{-- Пагинация --}}
            @if($movies->hasPages())
                <div class="pagination-wrapper">
                    {{ $movies->links() }}
                </div>
            @endif

            {{-- Другие жанры --}}
            @if($popularGenres->isNotEmpty())
                <div class="other-genres">
                    <h2 class="section-title">Другие жанры</h2>
                    <div class="genres-list">
                        @foreach($popularGenres as $otherGenre)
                            @if($otherGenre->id != $genre->id)
                                <a href="{{ route('main.genres', $otherGenre->id) }}"
                                   class="genre-tag">
                                    {{ ucfirst($otherGenre->name) }}
                                    <span class="genre-tag__count">{{ $otherGenre->movies_count }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
