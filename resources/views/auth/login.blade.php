@extends('layouts.main')

@section('content')
    <section class="auth">
        <div class="auth__container">
            <h1 class="auth__title">Вход</h1>

            {{-- Ошибки --}}
            @if ($errors->any())
                <ul class="error-messages">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('login') }}" class="auth__form">
                @csrf

                <div class="auth__form-content">
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
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

                    <label style="color:#aaa; font-size:14px; margin-bottom:10px;">
                        <input type="checkbox" name="remember">
                        Запомнить меня
                    </label>

                    <button type="submit" class="auth__button">
                        Войти
                    </button>
                </div>
            </form>

            <p class="auth__text">
                Нет аккаунта?
                <a href="{{ route('register') }}" class="auth__link">Регистрация</a>
            </p>

            @if (Route::has('password.request'))
                <p class="auth__text">
                    <a href="{{ route('password.request') }}" class="auth__link">
                        Забыли пароль?
                    </a>
                </p>
            @endif
        </div>
    </section>
@endsection
