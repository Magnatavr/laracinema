@extends('layouts.main')

@section('title', 'Смена пароля')

@section('content')
    <div class="change-password-page">
        <div class="container">
            <div class="change-password-header">
                <h1 class="page-title">Смена пароля</h1>
                <a href="{{ route('main.profile.edit') }}" class="btn btn--secondary">
                    <i class="fas fa-arrow-left"></i> Назад к редактированию
                </a>
            </div>

            <form method="POST" action="{{ route('main.profile.password.update') }}" class="change-password-form">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <div class="form-group">
                        <label for="current_password" class="form-label">Текущий пароль *</label>
                        <input type="password"
                               name="current_password"
                               id="current_password"
                               class="form-input"
                               required>
                        @error('current_password')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Новый пароль *</label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-input"
                               required
                               minlength="8">
                        <small class="form-hint">Минимум 8 символов</small>
                        @error('password')
                        <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Повторите новый пароль *</label>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="form-input"
                               required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn--primary">
                        <i class="fas fa-key"></i> Сменить пароль
                    </button>
                    <a href="{{ route('main.profile.index') }}" class="btn btn--secondary">
                        Отмена
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
