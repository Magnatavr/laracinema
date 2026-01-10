@extends('layouts.main')

@section('content')
    <section class="auth">
        <div class="auth__container">
            <h1 class="auth__title">Восстановление пароля</h1>

            {{-- Успешное сообщение --}}
            @if (session('status'))
                <div class="auth__success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="auth__form">
                @csrf

                {{-- Email --}}
                <div class="auth__form-content">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="auth__input @error('email') is-invalid @enderror"
                        placeholder="Введите email"
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

                <button type="submit" class="auth__button">
                    Отправить ссылку
                </button>
            </form>

            <div class="auth__text">
                <a href="{{ route('login') }}" class="auth__link">Вернуться к входу</a>
            </div>
        </div>
    </section>
@endsection
