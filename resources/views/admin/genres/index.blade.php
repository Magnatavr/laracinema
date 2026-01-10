@extends('admin.layouts.main')

@section('title', 'Жанры')

@section('content')
    <div class="container-fluid">

        {{-- Row с заголовком и хлебными крошками --}}
        <div class="row mb-3">
            <div class="col-sm-6">
                <h3 class="mb-0">Фильмы</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.main.index') }}">Домой</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Жанры</li>
                </ol>
            </div>
        </div>
        <!--end::Row-->

        {{-- Кнопка Назад --}}
        <div class="mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">← Назад</a>
        </div>

        <div class="d-flex justify-content-between mb-3">
            <h1>Жанры</h1>
            <a href="{{ route('admin.genres.create') }}" class="btn btn-primary m-1">Добавить жанр</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Действия</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($genres as $genre)
                        <tr>
                            <td>{{ $genre->id }}</td>
                            <td>{{ $genre->name }}</td>
                            <td>
                                <a href="{{ route('admin.genres.edit', $genre->id) }}" class="btn btn-sm btn-warning m-1">Редактировать</a>

                                <form action="{{ route('admin.genres.delete', $genre->id) }}"
                                      method="POST" class="d-inline m-1"
                                      onsubmit="return confirm('Удалить жанр?');">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger">Удалить</button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Нет жанров</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
