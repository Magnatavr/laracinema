@extends('admin.layouts.main')

@section('title', 'Фильмы')

@section('content')
    <div class="container">

        {{-- Row с заголовком и хлебными крошками --}}
        <div class="row mb-3">
            <div class="col-sm-6">
                <h3 class="mb-0">Фильмы</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.main.index') }}">Домой</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Фильмы</li>
                </ol>
            </div>
        </div>
        <!--end::Row-->

        {{-- Кнопка Назад --}}
        <div class="mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">← Назад</a>
        </div>

        {{-- Кнопка Добавить фильм --}}
        <a href="{{ route('admin.movies.create') }}" class="btn btn-primary mb-3">
            Добавить фильм
        </a>

        @if($movies->count())
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Год</th>
                    <th>Рейтинг</th>
                    <th>Статус</th>
                    <th>Жанры</th>
                    <th width="200">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach($movies as $movie)
                    <tr>
                        <td>{{ $movie->id }}</td>
                        <td>{{ $movie->title }}</td>
                        <td>{{ $movie->year }}</td>
                        <td>{{ $movie->rating }}</td>
                        <td>{{ $movie->status }}</td>
                        <td>
                            @foreach($movie->genres as $genre)
                                <span class="badge bg-secondary">{{ $genre->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('admin.movies.show', $movie->id) }}" class="btn btn-sm btn-info m-1">
                                Смотреть
                            </a>

                            <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-sm btn-warning m-1">
                                Редактировать
                            </a>

                            <form action="{{ route('admin.movies.delete', $movie->id) }}"
                                  method="POST"
                                  style="display:inline-block" class="m-1">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Удалить?')">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p>Фильмов пока нет.</p>
        @endif
    </div>
@endsection
