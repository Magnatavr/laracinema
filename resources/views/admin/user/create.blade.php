@extends('admin.layouts.main')

@section('title', 'Создать пользователя')

@section('content')
    <div class="container-fluid">

        <div class="row mb-3">
            <div class="col-sm-6">
                <h3>Создать пользователя</h3>
            </div>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="card card-body">

                <div class="mb-3">
                    <label class="form-label">Имя</label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}">

                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}">

                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Пароль</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror">

                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Роль</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror">
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Пользователь</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Администратор</option>
                    </select>

                    @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <button class="btn btn-primary">Создать</button>

                </div>
            </div>

        </form>

    </div>
@endsection
