<?php

namespace App\Http\Services;

use App\Core\Logic\Result;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoragePhotoService
{
    /**
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $path
     * @return Result
     */
    public function store(UploadedFile $file): Result
    {

        // generates a unique name for the photo.
        $filename = Str::uuid() . '.' . $file->extension();

        // moves the photo to the uploads folder.
        $file->storeAs('public/uploads', $filename);

        $result = Result::success([
            'success' => 'Arquivo salvo com sucesso',
            'filename' => $filename,
        ]);


        return $result;
    }

    /**
     * @param  string  $path
     * @param  string  $name
     * @return Result
     */
    public function remove(string $name): Result
    {
        if (!Storage::exists('public/uploads/' . $name)) {
            $result = Result::fail([
                'fail' => 'Arquivo não encontrado',
            ]);
        } else {
            if (!Storage::delete('public/uploads/' . $name)) {
                $result = Result::fail([
                    'fail' => 'Erro ao remover arquivo',
                ]);
            } else {
                $result = Result::success([
                    'success' => 'Arquivo removido com sucesso',
                ]);
            }
        }

        return $result;
    }
}
