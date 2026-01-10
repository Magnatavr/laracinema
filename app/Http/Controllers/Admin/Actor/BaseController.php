<?php

namespace App\Http\Controllers\Admin\Actor;

use App\Http\Controllers\Controller;
use App\Services\Admin\ActorService;


class BaseController extends Controller
{
    /**
     * Сервис для работы с актерами
     *
     * @var ActorService
     */
    public $actorService;

    /**
     * Конструктор контроллера
     *
     * @param ActorService $actorService Сервис актеров
     */
    public function __construct(ActorService $actorService)
    {
        $this->actorService = $actorService;
    }



}
