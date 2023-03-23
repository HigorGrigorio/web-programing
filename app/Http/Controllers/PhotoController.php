<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Services\StoragePhotoService;
use App\Interfaces\UserServiceInterface;

class PhotoController extends Controller
{
    public function __construct(
        private StoragePhotoService $storagePhotoService,
        private UserServiceInterface $userService,
    ) {
    }

    public function upload($id, PhotoRequest $request)
    {
        if ($request->hasFile('photo')) {
            $user = User::find($id);

            if (!$user) {
                $result = response('Usuário não encontrado', 404);
            }

            // gets photo.
            $photo = $request->file('photo');

            $storeResult = $this->storagePhotoService->store($photo);

            if ($storeResult->isFailure()) {
                $result = response($storeResult->getError(), 400);
            } else {
                // remove the actual photo from storage.
                if ($user->photo) {
                    $removeResult = $this->storagePhotoService->remove($user->photo);

                    if ($removeResult->isFailure()) {
                        $result = response($removeResult->getError(), 400);
                    }
                }

                $filename = $storeResult->getObject()['filename'];

                // updates the user's photo.
                $user->photo = $filename;
                $user->save();

                $result = response($storeResult->getObject(), 200);
            }
        }

        return $result;
    }

    public function remove($id)
    {
        $findResult = $this->userService->getById($id);

        if ($findResult->isFailure()) {
            $result = response($findResult->getError(), 404);
        } else {
            $user = $findResult->getObject();

            // remove the actual photo from storage.
            if ($user->photo) {
                $removeResult = $this->storagePhotoService->remove($user->photo);

                if ($removeResult->isFailure()) {
                    $result = response($removeResult->getError(), 400);
                }
            }

            $result = response($removeResult->getObject(), 200);

            // updates the user's photo.
            $user->photo = null;

            // TODO: update method.
            $updateResult = $this->userService->update($user->toArray(), $id);

            if ($updateResult->isFailure()) {
                $result = response($updateResult->getError(), 400);
            }
        }

        return $result;
    }
}
