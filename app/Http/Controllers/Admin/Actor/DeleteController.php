<?php

namespace App\Http\Controllers\Admin\Actor;

use App\Models\Actor;

class DeleteController extends BaseController
{
    public function __invoke(Actor $actor)
    {
        // удаляем актера через сервис
        $this->actorService->deleteActor($actor);


        return redirect()->route('admin.actors.index')->with('success', 'Актёр удалён');
    }
}
