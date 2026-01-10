@extends('layouts.main')

@section('content')
    <section class="auth">
        <div class="auth__container">
            <h1 class="auth__title">Сброс пароля</h1>

            <form method="POST" action="{{ route('password.update') }}" class="auth__form">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email --}}
                <div class="auth__form-content">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ $email ?? old('email') }}"
                        class="auth__input @error('email') is-invalid @enderror"
                        placeholder="Email"
                        required
                        autocomplete="email"
                        autofocus
                    >

                    @error('email')
                    <div class="error-messages">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Новый пароль --}}
                <div class="auth__form-content">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="auth__input @error('password') is-invalid @enderror"
                        placeholder="Новый пароль"
                        required
                        autocomplete="new-password"
                    >

                    @error('password')
                    <div class="error-messages">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Подтверждение --}}
                <div class="auth__form-content">
                    <input
                        id="password-confirm"
                        type="password"
                        name="password_confirmation"
                        class="auth__input"
                        placeholder="Повторите пароль"
                        required
                        autocomplete="new-password"
                    >
                </div>

                <button type="submit" class="auth__button">
                    Сбросить пароль
                </button>
            </form>
        </div>
    </section>
@endsection
