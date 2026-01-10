@extends('admin.layouts.main')


@section('title', $movie->title)

@section('content')
    <div class="container-fluid">

        {{-- Header + breadcrumbs --}}
        <div class="row mb-3">
            <div class="col-sm-6">
                <h3 class="mb-0">{{ $movie->title }}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.main.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.movies.index') }}">Фильмы</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $movie->title }}
                    </li>
                </ol>
            </div>
        </div>
        <!--end::Row-->

        {{-- Action buttons --}}
        <div class="mb-4">
            <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary me-2 mb-2">
                ← Назад
            </a>

            <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-warning me-2 mb-2">
                Редактировать
            </a>
        </div>

        {{-- Main info --}}
        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Год:</strong> {{ $movie->year ?? '—' }}</p>
                <p><strong>Рейтинг:</strong> {{ $movie->rating ?? '—' }}</p>
                <p><strong>Возрастной рейтинг:</strong> {{ \App\Models\Movie::$ageLabels[$movie->age_rating] ?? $movie->age_rating }}</p>
                <p><strong>Статус:</strong>
                    <span class="badge {{ $movie->status === 'published' ? 'bg-success' : 'bg-secondary' }}">
                {{ $movie->status }}
            </span>
                </p>
                @if($movie->duration)
                    @php
                        $hours = intdiv($movie->duration, 3600);
                        $minutes = intdiv($movie->duration % 3600, 60);
                        $seconds = $movie->duration % 60;
                    @endphp
                    <p><strong>Длительность:</strong>
                        {{ $hours > 0 ? $hours . 'ч ' : '' }}
                        {{ $minutes > 0 ? $minutes . 'м ' : '' }}
                        {{ $seconds > 0 ? $seconds . 'с' : '' }}
                    </p>
                @endif

                @if($movie->description)
                    <hr>
                    <p>{{ $movie->description }}</p>
                @endif
            </div>
        </div>


        {{-- Images --}}
        <div class="row mb-4">
            @if($movie->poster)
                <div class="col-md-4 mb-3">
                    <h5>Постер</h5>
                    <img src="{{ asset('storage/' . $movie->poster) }}"
                         class="img-fluid rounded shadow-sm">
                </div>
            @endif

            @if($movie->banner)
                <div class="col-md-8 mb-3">
                    <h5>Баннер</h5>
                    <img src="{{ asset('storage/' . $movie->banner) }}"
                         class="img-fluid rounded shadow-sm">
                </div>
            @endif
        </div>

        {{-- Videos --}}
        <div class="row mb-4">
            @if($movie->url)
                <div class="col-md-6 mb-3">
                    <h5>Фильм</h5>
                    <video controls class="w-100 rounded">
                        <source src="{{ asset('storage/' . $movie->url) }}">
                        Ваш браузер не поддерживает видео.
                    </video>
                </div>
            @endif

            @if($movie->trailer_url)
                <div class="col-md-6 mb-3">
                    <h5>Трейлер</h5>
                    <video controls class="w-100 rounded">
                        <source src="{{ asset('storage/' . $movie->trailer_url) }}">
                        Ваш браузер не поддерживает видео.
                    </video>
                </div>
            @endif
        </div>

        {{-- Genres --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5>Жанры</h5>
                @if($movie->genres->count())
                    @foreach($movie->genres as $genre)
                        <span class="badge bg-secondary me-1 mb-1">{{ $genre->name }}</span>
                    @endforeach
                @else
                    <p class="text-muted">Жанры не указаны</p>
                @endif
            </div>
        </div>

        {{-- Actors --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5>Актёры</h5>
                @if($movie->actors->count())
                    <ul class="mb-0">
                        @foreach($movie->actors as $actor)
                            <li>{{ $actor->name }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Актёры не указаны</p>
                @endif
            </div>
        </div>

    </div>
@endsection
