@extends('admin.layouts.main')

@section('title', 'Добавить жанр')

@section('content')
    <div class="container-fluid">
        {{-- Row с заголовком и хлебными крошками --}}
        <div class="row mb-3">
            <div class="col-sm-6">
                <h3 class="mb-0">Фильмы</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.genres.index') }}">Жанры</a></li>
                    <li class="breadcrumb-item active" aria-current="page"> Добавить жанр</li>
                </ol>
            </div>
        </div>
        <!--end::Row-->

        {{-- Кнопка Назад --}}
        <div class="mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">← Назад</a>
        </div>
        <h1>Добавить жанр</h1>

        <div class="card card-primary mt-3">
            <div class="card-body">

                <form action="{{ route('admin.genres.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Название жанра</label>
                        <input type="text" name="name" class="form-control" required>

                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button class="btn btn-success mt-3">Сохранить</button>
                    <a href="{{ route('admin.genres.index') }}" class="btn btn-secondary mt-3">Назад</a>

                </form>

            </div>
        </div>

    </div>
@endsection
