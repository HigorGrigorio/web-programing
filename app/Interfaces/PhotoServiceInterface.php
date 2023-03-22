<?php

use App\Core\Logic\Result;
use App\Core\Logic\Maybe;

interface PhotoServiceInterface
{
    public function store($photo, Maybe $filename): Result;
    public function delete($filename): Result;
    public function update($photo, Maybe $filename): Result;
}
