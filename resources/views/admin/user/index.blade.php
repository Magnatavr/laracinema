@extends('admin.layouts.main')

@section('title', 'Users')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="row mb-3">
            <div class="col-sm-6">
                <h3 class="mb-0">Пользователи</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.main.index') }}">Домой</a>
                    </li>
                    <li class="breadcrumb-item active">Пользователи</li>
                </ol>
            </div>
        </div>
        <!--end::Row-->

        {{-- Actions --}}
        <div class="mb-3">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                Добавить пользователя
            </a>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th>Создан</th>
                        <th width="180">Действия</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name ?? '—' }}</td>
                            <td>{{ $user->email }}</td>

                            <td>
                            <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-info' }}">
                                {{ $user->role }}
                            </span>
                            </td>

                            <td>{{ $user->created_at->format('d.m.Y') }}</td>

                            <td>
                                {{-- SHOW --}}
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   class="btn btn-sm btn-secondary me-1">
                                    Смотреть
                                </a>

                                {{-- EDIT --}}
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="btn btn-sm btn-warning me-1">
                                    Редактировать
                                </a>

                                {{-- DELETE --}}
                                <form action="{{ route('admin.users.delete', $user->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Удалить пользователя?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        Удалить
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Пользователей пока нет
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $users->links() }}
        </div>

    </div>
@endsection
