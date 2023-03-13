<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoController extends Controller
{
    public function upload($id, PhotoRequest $request)
    {
        if ($request->hasFile('photo')) {
            $user = User::find($id);

            if (!$user) {
                return response('Usuário não encontrado', 404);
            }

            // gets photo.
            $photo = $request->file('photo');

            // generates a unique name for the photo.
            $filename = Str::uuid() . '.' . $photo->extension();

            // moves the photo to the uploads folder.
            $photo->storeAs('public/uploads', $filename);

            // remove the actual photo from storage.
            if ($user->photo) {
                Storage::delete('public/uploads/' . $user->photo);
            }

            // updates the user's photo.
            $user->photo = $filename;
            $user->save();

            return response($filename, 200);
        }

        return response('Foto não encontrada', 400);
    }

    public function remove($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response('Usuário não encontrado', 404);
        }

        // remove the actual photo from storage.
        if ($user->photo) {
            Storage::delete('public/uploads/' . $user->photo);
        }

        // updates the user's photo.
        $user->photo = null;
        $user->save();

        return response('Foto removida', 200);
    }
}
