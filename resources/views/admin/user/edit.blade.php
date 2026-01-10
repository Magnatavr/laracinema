@extends('admin.layouts.main')

@section('title', 'Редактировать пользователя')

@section('content')
    <div class="container-fluid">

        <div class="row mb-3">
            <div class="col-sm-6">
                <h3>Редактировать пользователя</h3>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="card card-body">

                <div class="mb-3">
                    <label class="form-label">Имя</label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}">

                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}">

                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Новый пароль</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror">

                    <div class="form-text">Оставьте пустым, если не хотите менять</div>

                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Роль</label>
                    <select name="role"
                            class="form-select @error('role') is-invalid @enderror">

                        <option value="user" @selected(old('role', $user->role) === 'user')>Пользователь</option>
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Администратор</option>
                    </select>

                    @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-success">Сохранить</button>

            </div>

        </form>

    </div>
@endsection
