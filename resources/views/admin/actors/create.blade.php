@extends('admin.layouts.main')

@section('content')
    <div class="container">

        {{-- Заголовок + хлебные крошки --}}
        <div class="row mb-3">
            <div class="col-sm-6">
                <h3 class="mb-0">Актёры</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.actors.index') }}">Актёры</a>
                    </li>
                    <li class="breadcrumb-item active">Добавить</li>
                </ol>
            </div>
        </div>

        {{-- Кнопка назад --}}
        <div class="mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">← Назад</a>
        </div>

        <form action="{{ route('admin.actors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">

                {{-- Name --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Имя</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Birthday --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Дата рождения</label>
                    <input type="date" name="birthday" class="form-control" value="{{ old('birthday') }}">
                    @error('birthday') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Photo --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Фото</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                    @error('photo') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Bio --}}
                <div class="col-12 mb-3">
                    <label class="form-label">Биография</label>
                    <textarea name="bio" rows="4" class="form-control">{{ old('bio') }}</textarea>
                    @error('bio') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

            </div>

            <button class="btn btn-success">Создать</button>
        </form>

    </div>
@endsection
