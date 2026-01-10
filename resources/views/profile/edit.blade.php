@extends('layouts.main')

@section('title', 'Редактирование профиля')

@section('content')
    <div class="profile-edit-page">
        <div class="container">
            <div class="profile-edit-header">
                <h1 class="page-title">Редактирование профиля</h1>
                <a href="{{ route('main.profile.index') }}" class="btn btn--secondary">
                    <i class="fas fa-arrow-left"></i> Назад к профилю
                </a>
            </div>

            <form method="POST" action="{{ route('main.profile.update') }}" class="profile-edit-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Аватарка --}}
                <div class="form-section">
                    <h3 class="form-section__title">Аватар</h3>
                    <div class="avatar-upload">
                        <div class="avatar-upload__preview">
                            <img src="{{ $user->avatar_url }}"
                                 alt="Текущий аватар"
                                 id="avatar-preview">
                        </div>
                        <label for="avatar" class="avatar-upload__label">
                            <input type="file"
                                   name="avatar"
                                   id="avatar"
                                   class="avatar-upload__input"
                                   accept="image/*">
                            <span class="btn btn--secondary">
                            <i class="fas fa-upload"></i> Загрузить новое фото
                        </span>
                        </label>
                    </div>
                </div>

                {{-- Основная информация --}}
                <div class="form-section">
                    <h3 class="form-section__title">Основная информация</h3>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name" class="form-label">Имя *</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-input"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                            <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-input"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                            <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">Телефон</label>
                            <input type="tel"
                                   name="phone"
                                   id="phone"
                                   class="form-input"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="+7XXXXXXXXXX">
                            <small class="form-hint">Формат: +7XXXXXXXXXX</small>
                            @error('phone')
                            <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="birth_date" class="form-label">Дата рождения</label>
                            <input type="date"
                                   name="birth_date"
                                   id="birth_date"
                                   class="form-input"
                                   value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}"
                                   max="{{ date('Y-m-d') }}">
                            @error('birth_date')
                            <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="city" class="form-label">Город</label>
                            <input type="text"
                                   name="city"
                                   id="city"
                                   class="form-input"
                                   value="{{ old('city', $user->city) }}">
                            @error('city')
                            <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- О себе --}}
                <div class="form-section">
                    <h3 class="form-section__title">О себе</h3>
                    <div class="form-group">
                        <label for="about" class="form-label">Расскажите о себе</label>
                        <textarea name="about"
                                  id="about"
                                  class="form-textarea"
                                  rows="5">{{ old('about', $user->about) }}</textarea>
                        <small class="form-hint">Максимум 1000 символов</small>
                        @error('about')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Настройки --}}
                <div class="form-section">
                    <h3 class="form-section__title">Настройки</h3>
                    <div class="form-group">
                        <label class="form-checkbox">
                            <input type="checkbox"
                                   name="email_notifications"
                                   value="1"
                                {{ old('email_notifications', $user->email_notifications) ? 'checked' : '' }}>
                            <span class="form-checkbox__label">
                            Получать уведомления на email
                        </span>
                        </label>
                    </div>
                </div>

                {{-- Кнопки --}}
                <div class="form-actions">
                    <button type="submit" class="btn btn--primary">
                        <i class="fas fa-save"></i> Сохранить изменения
                    </button>
                    <a href="{{ route('main.profile.password.edit') }}" class="btn btn--secondary">
                        <i class="fas fa-key"></i> Сменить пароль
                    </a>
                </div>
            </form>
        </div>
    </div>


@endsection
