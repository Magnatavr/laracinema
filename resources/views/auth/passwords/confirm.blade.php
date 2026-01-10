@extends('layouts.main')

@section('content')
    <section class="auth">
        <div class="auth__container">
            <h1 class="auth__title">Подтвердите пароль</h1>

            <p class="auth__subtitle">
                Для продолжения введите текущий пароль
            </p>

            <form method="POST" action="{{ route('password.confirm') }}" class="auth__form">
                @csrf

                <div class="auth__form-content">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="auth__input @error('password') is-invalid @enderror"
                        placeholder="Пароль"
                        required
                        autocomplete="current-password"
                    >

                    @error('password')
                    <div class="error-messages">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <button type="submit" class="auth__button">
                    Подтвердить
                </button>
            </form>

            @if (Route::has('password.request'))
                <div class="auth__text">
                    <a href="{{ route('password.request') }}" class="auth__link">
                        Забыли пароль?
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection
