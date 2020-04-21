<?php

namespace App\Services;

use Carbon\Carbon;
use Dflydev\ApacheMimeTypes\PhpRepository;
use Illuminate\Support\Facades\Storage;

class SignmentUploadsManager extends FileUploadsManager
{
    public function __construct(PhpRepository $mimeDetect)
    {
        $this->disk = Storage::disk(config('signment.signments.storage'));
        $this->mimeDetect = $mimeDetect;
    }

    /**
     * 返回文件完整的web路径
     */
    public function fileWebpath($path)
    {
        $path = rtrim(config('signment.signments.webpath'), '/') . '/' . ltrim($path, '/');
        return url($path);
    }
}
