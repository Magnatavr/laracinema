@extends('admin.layouts.main')

@section('title', 'Редактировать актёра')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="row mb-3">
            <div class="col-sm-6">
                <h3 class="mb-0">Редактировать актёра</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.actors.index') }}">Актёры</a>
                    </li>
                    <li class="breadcrumb-item active">Редактирование</li>
                </ol>
            </div>
        </div>

        <form action="{{ route('admin.actors.update', $actor->id) }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            {{-- Name --}}
            <div class="mb-3">
                <label class="form-label">Имя</label>
                <input type="text" name="name"
                       class="form-control"
                       value="{{ old('name', $actor->name) }}">
                @error('name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            {{-- Birthday --}}
            <div class="mb-3">
                <label class="form-label">Дата рождения</label>
                <input type="date" name="birthday"
                       class="form-control"
                       value="{{ old('birthday', optional($actor->birthday)->format('Y-m-d')) }}">
                @error('birthday') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            {{-- Bio --}}
            <div class="mb-3">
                <label class="form-label">Биография</label>
                <textarea name="bio" class="form-control" rows="4">{{ old('bio', $actor->bio) }}</textarea>
            </div>

            {{-- Photo --}}
            <div class="mb-3">
                <label class="form-label">Фото</label>
                <input type="file" name="photo" class="form-control">

                @if($actor->photo)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $actor->photo) }}" width="100">
                    </div>
                @endif
            </div>

            <button class="btn btn-success">Сохранить</button>
            <a href="{{ route('admin.actors.index') }}" class="btn btn-secondary">
                Назад
            </a>
        </form>

    </div>
@endsection
