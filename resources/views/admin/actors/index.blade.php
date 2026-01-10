@extends('admin.layouts.main')

@section('title', 'Актёры')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="row mb-3">
            <div class="col-sm-6">
                <h3 class="mb-0">Актёры</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.main.index') }}">Домой</a>
                    </li>
                    <li class="breadcrumb-item active">Актёры</li>
                </ol>
            </div>
        </div>
        <!--end::Row-->

        {{-- Actions --}}
        <div class="mb-3">
            <a href="{{ route('admin.actors.create') }}" class="btn btn-primary">
                Добавить актёра
            </a>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Фото</th>
                        <th>Имя</th>
                        <th>Дата рождения</th>
                        <th width="180">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($actors as $actor)
                        <tr>
                            <td>{{ $actor->id }}</td>
                            <td>
                                @if($actor->photo)
                                    <img src="{{ asset('storage/' . $actor->photo) }}"
                                         width="50" class="img-circle">
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $actor->name }}</td>
                            <td>{{ $actor->birthday_formatted ?? '—' }}</td>
                            <td>
                                <a href="{{ route('admin.actors.edit', $actor->id) }}"
                                   class="btn btn-sm btn-warning me-1">
                                    Редактировать
                                </a>

                                <form action="{{ route('admin.actors.delete', $actor->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Удалить актёра?')">
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
                            <td colspan="5" class="text-center">
                                Актёров пока нет
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $actors->links() }}
        </div>

    </div>
@endsection
