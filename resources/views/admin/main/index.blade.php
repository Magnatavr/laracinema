@extends('admin.layouts.main')
@section('content')
    <div class="app-wrapper">

        @include('admin.layouts.partials.navbar')
        @include('admin.layouts.partials.sidebar')

        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Панель управления</h3>
                            <p class="text-muted mb-0">Статистика и аналитика платформы</p>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="{{ route('admin.main.index') }}">Главная</a></li>
                                <li class="breadcrumb-item active">Панель управления</li>
                            </ol>
                            <div class="float-sm-end mt-2">
                                <button class="btn btn-sm btn-outline-secondary" id="refresh-stats">
                                    <i class="bi bi-arrow-clockwise"></i> Обновить
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::App Content Header-->

            <!--begin::App Content-->
            <div class="app-content">
                <div class="container-fluid">

                    <!-- Статистические виджеты -->
                    <div class="row mb-4" id="dashboard-widgets">
                        @foreach($widgets as $widget)
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                                <div class="card card-widget border-0 shadow-sm mb-3">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted text-uppercase small">{{ $widget['title'] }}</h6>
                                                <h3 class="fw-bold mb-0">{{ $widget['value'] }}</h3>
                                            </div>
                                            <div class="icon-circle bg-{{ $widget['color'] }} text-white">
                                                <i class="bi bi-{{ $widget['icon'] }}"></i>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            @if(isset($widget['change']))
                                                <small class="d-flex align-items-center">
                                                    @if($widget['change']['trend'] == 'up')
                                                        <i class="bi bi-arrow-up text-success me-1"></i>
                                                        <span class="text-success">+{{ $widget['change']['percent'] }}%</span>
                                                    @else
                                                        <i class="bi bi-arrow-down text-danger me-1"></i>
                                                        <span class="text-danger">{{ $widget['change']['percent'] }}%</span>
                                                    @endif
                                                    <span class="text-muted ms-2">за месяц</span>
                                                </small>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Основные секции -->
                    <div class="row">

                        <!-- Левая колонка: Графики и активность -->
                        <div class="col-lg-8">

                            <!-- График роста -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-white border-0">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-graph-up text-primary me-2"></i>
                                        Рост контента за 6 месяцев
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="growthChart" height="250"></canvas>
                                </div>
                            </div>

                            {{-- Последние действия --}}
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-white border-0">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-clock-history text-warning me-2"></i>
                                        Последние действия
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        @if(isset($stats['recentActivity']) && count($stats['recentActivity']) > 0)
                                            @foreach($stats['recentActivity'] as $activity)
                                                @if(isset($activity['link']) && $activity['link'])
                                                    <a href="{{ $activity['link'] }}"
                                                       class="list-group-item list-group-item-action border-0 py-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="icon-circle-sm bg-{{ $activity['color'] ?? 'secondary' }} text-white me-3">
                                                                <i class="bi bi-{{ $activity['icon'] ?? 'info-circle' }}"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1">{{ $activity['title'] ?? 'Действие' }}</h6>
                                                                <p class="text-muted mb-0 small">{{ $activity['description'] ?? 'Описание отсутствует' }}</p>
                                                            </div>
                                                            <div class="text-muted small text-end">
                                                                {{ $activity['time'] ?? 'только что' }}
                                                            </div>
                                                        </div>
                                                    </a>
                                                @else
                                                    <div class="list-group-item border-0 py-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="icon-circle-sm bg-{{ $activity['color'] ?? 'secondary' }} text-white me-3">
                                                                <i class="bi bi-{{ $activity['icon'] ?? 'info-circle' }}"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1">{{ $activity['title'] ?? 'Действие' }}</h6>
                                                                <p class="text-muted mb-0 small">{{ $activity['description'] ?? 'Описание отсутствует' }}</p>
                                                            </div>
                                                            <div class="text-muted small text-end">
                                                                {{ $activity['time'] ?? 'только что' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="list-group-item border-0 py-4">
                                                <div class="text-center text-muted py-3">
                                                    <i class="bi bi-inbox display-4"></i>
                                                    <p class="mt-2 mb-0">Нет данных о последних действиях</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Распределение по жанрам -->
                            <div class="card shadow-sm">
                                <div class="card-header bg-white border-0">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-tags text-success me-2"></i>
                                        Топ жанров по количеству фильмов
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($stats['genres']['distribution'] as $genre)
                                            <div class="col-md-6 mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted">{{ $genre['name'] }}</span>
                                                    <div class="d-flex align-items-center">
                                                        <span class="fw-bold me-2">{{ $genre['movies_count'] }}</span>
                                                        <span class="badge bg-light text-dark">{{ $genre['percentage'] }}%</span>
                                                    </div>
                                                </div>
                                                <div class="progress mt-1" style="height: 8px;">
                                                    <div class="progress-bar bg-success"
                                                         style="width: {{ $genre['percentage'] }}%"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Правая колонка: Топы и здоровье системы -->
                        <div class="col-lg-4">

                            <!-- Топ фильмов по рейтингу -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-white border-0">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-trophy text-warning me-2"></i>
                                        Топ фильмов по рейтингу
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        @foreach($stats['topMovies'] as $index => $movie)
                                            <a href="{{ route('admin.movies.edit', $movie['id']) }}"
                                               class="list-group-item list-group-item-action border-0 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="position-relative me-3">
                                                        <div class="rank-badge bg-primary text-white">
                                                            {{ $index + 1 }}
                                                        </div>
                                                        @if($movie['poster_url'])
                                                            <img src="{{ $movie['poster_url'] }}"
                                                                 alt="{{ $movie['title'] }}"
                                                                 class="rounded"
                                                                 style="width: 50px; height: 75px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                 style="width: 50px; height: 75px;">
                                                                <i class="bi bi-film text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 text-truncate">{{ $movie['title'] }}</h6>
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-star-fill text-warning me-1"></i>
                                                            <span class="fw-bold me-2">{{ $movie['rating'] }}</span>
                                                            <small class="text-muted">
                                                                <i class="bi bi-chat me-1"></i>{{ $movie['comments_count'] }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Здоровье системы -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-white border-0">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-heart-pulse text-danger me-2"></i>
                                        Здоровье системы
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $issues = $stats['systemHealth']['issues'] ?? [];
                                        $totalIssues = array_sum($issues);
                                    @endphp

                                    @if($totalIssues == 0)
                                        <div class="text-center py-3">
                                            <i class="bi bi-check-circle-fill text-success display-4"></i>
                                            <p class="mt-2 mb-0">Все системы работают нормально</p>
                                        </div>
                                    @else
                                        <div class="alert alert-{{ $issues['critical'] > 0 ? 'danger' : ($issues['warning'] > 0 ? 'warning' : 'info') }}">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-{{ $issues['critical'] > 0 ? 'exclamation-triangle' : 'info-circle' }} me-2"></i>
                                                <div>
                                                    <h6 class="alert-heading mb-1">
                                                        @if($issues['critical'] > 0)
                                                            Критические проблемы
                                                        @elseif($issues['warning'] > 0)
                                                            Предупреждения
                                                        @else
                                                            Информация
                                                        @endif
                                                    </h6>
                                                    <p class="mb-0 small">
                                                        Обнаружено {{ $totalIssues }} {{ trans_choice('проблема|проблемы|проблем', $totalIssues) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            @if($stats['systemHealth']['movies_without_poster'] > 0)
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="text-muted small">Фильмы без постера</span>
                                                    <span class="badge bg-danger">{{ $stats['systemHealth']['movies_without_poster'] }}</span>
                                                </div>
                                            @endif

                                            @if($stats['systemHealth']['movies_without_banner'] > 0)
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="text-muted small">Фильмы без баннера</span>
                                                    <span class="badge bg-warning">{{ $stats['systemHealth']['movies_without_banner'] }}</span>
                                                </div>
                                            @endif

                                            @if($stats['systemHealth']['actors_without_photo'] > 0)
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="text-muted small">Актеры без фото</span>
                                                    <span class="badge bg-warning">{{ $stats['systemHealth']['actors_without_photo'] }}</span>
                                                </div>
                                            @endif

                                            @if($stats['systemHealth']['movies_without_genres'] > 0)
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="text-muted small">Фильмы без жанров</span>
                                                    <span class="badge bg-info">{{ $stats['systemHealth']['movies_without_genres'] }}</span>
                                                </div>
                                            @endif

                                            @if($stats['systemHealth']['movies_without_actors'] > 0)
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted small">Фильмы без актеров</span>
                                                    <span class="badge bg-info">{{ $stats['systemHealth']['movies_without_actors'] }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                </div>
                            </div>

                            <!-- Быстрые действия -->
                            <div class="card shadow-sm">
                                <div class="card-header bg-white border-0">
                                    <h5 class="card-title mb-0">
                                        <i class="bi bi-lightning text-primary me-2"></i>
                                        Быстрые действия
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <a href="{{ route('admin.movies.create') }}" class="btn btn-outline-primary w-100">
                                                <i class="bi bi-plus-circle me-1"></i> Добавить фильм
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{ route('admin.actors.create') }}" class="btn btn-outline-success w-100">
                                                <i class="bi bi-person-plus me-1"></i> Добавить актера
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{ route('admin.genres.create') }}" class="btn btn-outline-info w-100">
                                                <i class="bi bi-tag-plus me-1"></i> Добавить жанр
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
    </div>
@endsection

@push('styles')
    <style>
        .icon-circle {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .icon-circle-sm {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rank-badge {
            position: absolute;
            top: -5px;
            left: -5px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            z-index: 1;
        }

        .card-widget {
            transition: transform 0.2s;
        }

        .card-widget:hover {
            transform: translateY(-3px);
        }

        .progress {
            background-color: #f0f0f0;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // График роста
            const growthCtx = document.getElementById('growthChart').getContext('2d');
            const growthChart = new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: @json(array_column($stats['monthlyGrowth'], 'month')),
                    datasets: [
                        {
                            label: 'Фильмы',
                            data: @json(array_column($stats['monthlyGrowth'], 'movies')),
                            borderColor: '#4e73df',
                            backgroundColor: 'rgba(78, 115, 223, 0.1)',
                            tension: 0.3
                        },
                        {
                            label: 'Актеры',
                            data: @json(array_column($stats['monthlyGrowth'], 'actors')),
                            borderColor: '#1cc88a',
                            backgroundColor: 'rgba(28, 200, 138, 0.1)',
                            tension: 0.3
                        },
                        {
                            label: 'Комментарии',
                            data: @json(array_column($stats['monthlyGrowth'], 'comments')),
                            borderColor: '#36b9cc',
                            backgroundColor: 'rgba(54, 185, 204, 0.1)',
                            tension: 0.3
                        },
                        {
                            label: 'Пользователи',
                            data: @json(array_column($stats['monthlyGrowth'], 'users')),
                            borderColor: '#f6c23e',
                            backgroundColor: 'rgba(246, 194, 62, 0.1)',
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            // Обновление статистики
            document.getElementById('refresh-stats').addEventListener('click', function() {
                const btn = this;
                const originalHtml = btn.innerHTML;

                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Обновление...';
                btn.disabled = true;

                fetch('{{ route("admin.dashboard.widgets") }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Обновляем виджеты
                            data.widgets.forEach((widget, index) => {
                                const widgetElement = document.querySelectorAll('.card-widget .fw-bold')[index];
                                if (widgetElement) {
                                    widgetElement.textContent = widget.value;
                                }
                            });

                            // Показываем уведомление
                            showNotification('success', 'Статистика обновлена');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('error', 'Ошибка при обновлении');
                    })
                    .finally(() => {
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    });
            });

            // Обновление графика
            function updateChart() {
                fetch('{{ route("admin.dashboard.growth") }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            growthChart.data.labels = data.labels;
                            growthChart.data.datasets[0].data = data.movies;
                            growthChart.data.datasets[1].data = data.actors;
                            growthChart.data.datasets[2].data = data.comments;
                            growthChart.data.datasets[3].data = data.users;
                            growthChart.update();
                        }
                    });
            }

            // Уведомления
            function showNotification(type, message) {
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                alert.style.top = '20px';
                alert.style.right = '20px';
                alert.style.zIndex = '9999';
                alert.style.minWidth = '300px';
                alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
                document.body.appendChild(alert);

                setTimeout(() => {
                    alert.remove();
                }, 3000);
            }

            // Автообновление каждые 5 минут
            setInterval(updateChart, 5 * 60 * 1000);
        });
    </script>
@endpush
