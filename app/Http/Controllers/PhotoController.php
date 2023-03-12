<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PhotoController extends Controller
{
    public function upload($id, Request $request)
    {
        if ($request->hasFile('photo')) {
            $user = User::find($id);

            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if (!$user) {
                return response('Usuário não encontrado', 404);
            }

            // gets photo.
            $photo = $request->file('photo');

            // generates a unique name for the photo.
            $filename = Str::uuid() . '.' . $photo->extension();

            // moves the photo to the uploads folder.
            $photo->move(public_path('/uploads'), $filename);

            // updates the user's photo.
            $user->photo = $filename;
            $user->save();

            return response($filename, 200);
        }

        return response('Foto não encontrada', 400);
    }
}
