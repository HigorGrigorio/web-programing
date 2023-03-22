<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Interfaces\UserServiceInterface;

class UserController extends Controller
{
    public function __construct(
        private UserServiceInterface $userService,
    ) {
    }

    public function index()
    {
        $result = $this->userService->getAll();

        if ($result->isFailure()) {
            return redirect('users')->with($result->getError());
        }

        $users = $result->getObject();

        return view(
            'user.index',
            [
                'users' => $users,
            ]
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

        $user = new User();

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
        $userResult = $this->userService->getById($id);

        if (!$userResult) {
            return redirect('users')->with($userResult->getData());
        }

        $user = $userResult->getObject();

        // checks if user has photo
        if ($user->photo) {
            // TODO: use photo service.
            // deletes the photo.
            Storage::delete('public/uploads/' . $user->photo);
        }

        // deletes the user.
        $this->userService->delete($id);

        return redirect('users')->with(['success' => 'Usuário excluído com sucesso.']);
    }

    public function edit($id)
    {
        $result = $this->userService->getById($id);

        if ($result->isFailure()) {
            return redirect('users')->with($result->getError());
        }

        $user = $result->getObject();

        return view('user.edit', compact('user'));
    }

    public function confirm($id)
    {
        $result = $this->userService->getById($id);

        if (!$result->isSuccess()) {
            return redirect('users')->with($result->getError());
        }

        $user = $result->getObject();

        return view('user.delete', compact('user'));
    }

    public function update($id, UserRequest $request)
    {
        $findResult = $this->userService->getById($id);

        if (!$findResult->isSuccess()) {
            return redirect('users')->with($findResult->getError());
        }

        $raw = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        $user = $findResult->getObject();

        $data = array_merge(
            [
                'name' => $user->name,
                'email' => $user->email,
            ],
            $raw
        );

        $updateResult = $this->userService->update($data, $id);

        return redirect('users')->with($updateResult->getObject());
    }
}
