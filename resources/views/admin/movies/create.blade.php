@extends('admin.layouts.main')

@section('content')
    <div class="container">
        {{-- Row с заголовком и хлебными крошками --}}
        <div class="row mb-3">
            <div class="col-sm-6">
                <h3 class="mb-0">Фильмы</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('admin.movies.index') }}">Фильмы</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Добавить фильм</li>
                </ol>
            </div>
        </div>
        <!--end::Row-->

        {{-- Кнопка Назад --}}
        <div class="mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">← Назад</a>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Добавить фильм</h1>

        </div>

        <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                {{-- Title --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Название</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                    @error('title') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Year --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Год</label>
                    <select name="year" class="form-select">
                        <option value="">—</option>
                        @for ($y = now()->year; $y >= 1900; $y--)
                            <option value="{{ $y }}" @selected(old('year') == $y)>{{ $y }}</option>
                        @endfor
                    </select>
                    @error('year') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Description --}}
                <div class="col-12 mb-3">
                    <label class="form-label">Описание</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Rating --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label">Рейтинг</label>
                    <input type="number" step="0.1" max="10" name="rating" class="form-control" value="{{ old('rating') }}">
                    @error('rating') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Age rating --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label d-block">Возрастной рейтинг</label>
                    @foreach(\App\Models\Movie::$ageLabels as $key => $label)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="age_rating" id="rating-{{ $key }}" value="{{ $key }}"
                                @checked(old('age_rating') == $key)>
                            <label class="form-check-label" for="rating-{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                    @error('age_rating') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Status --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label">Статус</label>
                    <select name="status" class="form-select">
                        <option value="draft" @selected(old('status')=='draft')>Черновик</option>
                        <option value="published" @selected(old('status')=='published')>Опубликован</option>
                    </select>
                    @error('status') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Poster --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Постер</label>
                    <input type="file" name="poster" class="form-control" accept="image/*">
                    @error('poster') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Banner --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Баннер</label>
                    <input type="file" name="banner" class="form-control" accept="image/*">
                    @error('banner') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Video --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Файл фильма</label>
                    <input type="file" id="url" name="url" class="form-control" accept="video/mp4,video/webm">
                    @error('url') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <input type="hidden" id="duration" name="duration" value="">

                {{-- Trailer --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Файл трейлера</label>
                    <input type="file" name="trailer_url" class="form-control" accept="video/mp4,video/webm">
                    @error('trailer_url') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Genres --}}
                <div class="col-12 mb-3">
                    <label class="form-label">Жанры</label>
                    <select name="genres[]" class="form-select selectpicker" multiple data-live-search="true" data-actions-box="true">
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}" @selected(collect(old('genres'))->contains($genre->id))>{{ $genre->name }}</option>
                        @endforeach
                    </select>
                    @error('genres') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Actors --}}
                <div class="col-12 mb-3">
                    <label class="form-label">Актеры</label>
                    <select name="actors[]" class="form-select selectpicker" multiple data-live-search="true" data-actions-box="true">
                        @foreach($actors as $actor)
                            <option value="{{ $actor->id }}" @selected(collect(old('actors'))->contains($actor->id))>{{ $actor->name }}</option>
                        @endforeach
                    </select>
                    @error('actors') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
            </div>

            <button class="btn btn-success mt-3">Создать</button>
        </form>
    </div>

    {{-- JS для видео и duration --}}
    <script>
        document.getElementById('url').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            const video = document.createElement('video');
            video.preload = 'metadata';
            video.src = URL.createObjectURL(file);
            video.onloadedmetadata = function() {
                document.getElementById('duration').value = Math.floor(video.duration);
                URL.revokeObjectURL(video.src);
            }
        });
    </script>


@endsection
