<?php

namespace App\Http\Controllers;

use Rules\Password;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = User::with('role')->paginate(10);
        $roles = Role::all()->map(function ($role) {
            return [
                'label' => $role->name,
                'value' => $role->id,
            ];
        });
        return Inertia::render('User/Index', compact('data', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required']
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role
        ]);

        return to_route('users.index');
    }
    public function update(Request $request, $id)
    {
        $validation = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($id),
            ],
            'role' => ['required']
        ];

        if ($request->password) {
            $validation['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        $request->validate($validation);

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return to_route('users.index');
    }
}
