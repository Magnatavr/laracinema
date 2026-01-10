<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        DB::transaction(function () use ($data, $request, $user) {

            if ($request->hasFile('avatar')) {

                // Проверяем, есть ли старый аватар и удаляем его
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $path = $request->file('avatar')->store('profile/avatars', 'public');
                $data['avatar'] = $path;
                $user->update($data);

            }
        });


        return redirect()->route('main.profile.index')->with('success', 'Профиль успешно обновлен!');
    }
}
