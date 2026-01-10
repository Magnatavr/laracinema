<?php

namespace App\Http\Controllers\Profile\Password;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\Password\UpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Текущий пароль неверен',
            ]);
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        Auth::logoutOtherDevices($data['password']);

        return redirect()
            ->route('main.profile.index')
            ->with('success', 'Пароль успешно изменён!');
    }
}
