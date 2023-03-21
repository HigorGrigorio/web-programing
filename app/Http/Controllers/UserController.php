<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


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

        // binds the request data to the user.
        $user->name = $request->name;
        $user->email = $request->email;

        // gets photo.
        $photo = $request->file('photo_');
        $filename = null;

        if ($photo) {
            // generates a unique name for the photo.
            $filename = Str::uuid() . '.' . $photo->extension();

            // moves the photo to the uploads folder.
            $photo->storeAs('public/uploads', $filename);
        }

        // binds the photo to the user.
        $user->photo = $filename;

        $user->save();

        return redirect('users')->with(['success' => 'Usuário cadastrado com sucesso.']);
    }

    public function delete($id)
    {
        $user = User::find($id);

        // checks if user exists.
        if (!$user) {
            return redirect('users')->with(['fail' => 'Usuário não encontrado.']);
        }

        // checks if user has a photo.
        if ($user->photo) {
            // removes the photo from storage.
            Storage::delete('public/uploads/' . $user->photo);
        }

        $user->delete();

        return redirect('users')->with(['success' => 'Usuário excluído com sucesso.']);
    }

    public function edit($id)
    {
        $user = User::find($id);

        // checks if user exists.
        if (!$user) {
            return redirect('users')->with(['fail' => 'Usuário não encontrado.']);
        }

        return view('user.edit', compact('user'));
    }

    public function confirm($id)
    {
        $user = User::find($id);

        // checks if user exists.
        if (!$user) {
            return redirect('users')->with(['fail' => 'Usuário não encontrado.']);
        }

        return view('user.delete', compact('user'));
    }

    public function update($id, UserRequest $request)
    {
        $user = User::find($id);

        // checks if user exists.
        if (!$user) {
            return redirect('users')->with(['fail' => 'Usuário não encontrado.']);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect('users')->with(['success' => 'Usuário atualizado com sucesso.']);
    }

    public function search(Request $request)
    {
        $raw = [
            'search' => $request->search,
            'offset' => $request->offset,
            'limit' => $request->limit,
        ];

        $options = array_merge(
            [
                'search' => '',
                'offset' => 0,
                'limit' => 0,
            ],
            $raw
        );

        if ($options['search'] == '') {
            return redirect('users');
        }

        $users = User::where('name', 'like', '%' . $options['search'] . '%')
            ->orWhere('email', 'like', '%' . $options['search'] . '%')
            ->orWhere('id', 'like', '%' . $options['search'] . '%');

        if ($options['limit'] > 0) {
            $users = $users->limit($options['limit']);
        }

        if ($options['offset'] > 0) {
            $users = $users->offset($options['offset']);
        }

        $users = $users->get();

        return view('user.index', compact('users'));
    }
}
