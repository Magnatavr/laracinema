@extends('layouts.main')

@section('title', 'LaraCinema')

@section('content')
    {{-- Hero Slider --}}
    <section class="hero">
        @include('components.hero-card', [
     'heroMovies' => $heroMovies,
     'type' => 'hero'
 ])
    </section>

    {{-- Новинки --}}
    @if($recentMovies->isNotEmpty())
        <section class="movies-section" id="recent-section">
            <div class="container">
                <h2 class="section-title">Новинки</h2>
                <div class="movies-flex" id="recent-movies-grid">
                    @include('components.movie-card', [
                        'movies' => $recentMovies,
                        'type' => 'recent'
                    ])
                </div>
            </div>
        </section>
    @endif

    {{-- Популярное --}}
    @if($popularMovies->isNotEmpty())
        <section class="movies-section" id="popular-section">
            <div class="container">
                <h2 class="section-title">Популярное</h2>
                <div class="movies-flex" id="popular-movies-grid">
                    @include('components.movie-card', [
                        'movies' => $popularMovies,
                        'type' => 'popular'
                    ])
                </div>
            </div>
        </section>
    @endif

    {{-- Топ 10 --}}
    @if($topMovies->isNotEmpty())
        <section class="movies-section" id="top-section">
            <div class="container">
                <h2 class="section-title">Топ 10 фильмов</h2>
                <div class="movies-flex" id="popular-movies-grid">
                    @include('components.movie-card', [
                        'movies' => $topMovies,
                        'type' => 'top-Movies'
                    ])
                </div>

            </div>
        </section>
    @endif
@endsection


