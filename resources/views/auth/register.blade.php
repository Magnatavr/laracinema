@extends('layouts.main')

@section('content')
    <section class="auth">
        <div class="auth__container">
            <h1 class="auth__title">Регистрация</h1>

            {{-- Ошибки --}}
            @if ($errors->any())
                <ul class="error-messages">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('register') }}" class="auth__form">
                @csrf

                <div class="auth__form-content">
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="Имя"
                        class="auth__input"
                    >

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="Email"
                        class="auth__input"
                    >

                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="Пароль"
                        class="auth__input"
                    >

                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        placeholder="Повторите пароль"
                        class="auth__input"
                    >

                    <button type="submit" class="auth__button">
                        Зарегистрироваться
                    </button>
                </div>
            </form>

            <p class="auth__text">
                Уже есть аккаунт?
                <a href="{{ route('login') }}" class="auth__link">Войти</a>
            </p>
        </div>
    </section>
@endsection
