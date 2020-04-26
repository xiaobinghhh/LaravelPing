<?php

namespace App\Services;

use Dflydev\ApacheMimeTypes\PhpRepository;
use Illuminate\Support\Facades\Storage;

class HomeworkUploadsManager extends FileUploadsManager
{
    public function __construct(PhpRepository $mimeDetect)
    {
        $this->disk = Storage::disk(config('homework.homeworks.storage'));
        $this->mimeDetect = $mimeDetect;
    }

    /**
     * 返回文件完整的web路径
     */
    public function fileWebpath($path)
    {
        $path = rtrim(config('homework.homeworks.webpath'), '/') . '/' . ltrim($path, '/');
        return url($path);

    }
}
