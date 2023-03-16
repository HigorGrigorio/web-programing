<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view(
            'user.index',
            compact('users')
            /**
            [
                'users' => $users
            ]
         */

        )->with(['success', 'Usuário cadastrado com sucesso']);
    }

    public function create()
    {
        return view('user.store');
    }

    public function store(UserRequest $request)
    {
        $request->validate([
            'email' => 'unique:users',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect('users')->with(['success' => 'Usuário cadastrado com sucesso.']);
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect('users')->with(['success' => 'Usuário excluído com sucesso.']);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('user.edit', compact('user'));
    }

    public function confirm($id)
    {
        $user = User::find($id);
        return view('user.delete', compact('user'));
    }

    public function update($id, UserRequest $request)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect('users')->with(['success' => 'Usuário atualizado com sucesso.']);
    }
}
