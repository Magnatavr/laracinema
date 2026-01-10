<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Services\HomePageService;
use App\Services\MovieFilterService;

class BaseController extends Controller
{
    /**
     * Сервис фильтрации фильмов
     *
     * @var MovieFilterService
     *
     * Сервис для работы с главной страницей
     * @var HomePageService
     */
    protected MovieFilterService $filterService;
    protected HomePageService $homeService;


    /**
     * Конструктор контроллера
     *
     * @param MovieFilterService $filterService Сервис фильтрации
     *
     * @param HomePageService $homeService Сервис главной страницы
     */
    public function __construct(MovieFilterService $filterService, HomePageService $homeService)
    {
        $this->filterService = $filterService;
        $this->homeService = $homeService;
    }


}
