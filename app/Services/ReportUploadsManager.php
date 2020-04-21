<?php

namespace App\Services;

use Dflydev\ApacheMimeTypes\PhpRepository;
use Illuminate\Support\Facades\Storage;

class ReportUploadsManager extends FileUploadsManager
{
    public function __construct(PhpRepository $mimeDetect)
    {
        $this->disk = Storage::disk(config('report.reports.storage'));
        $this->mimeDetect = $mimeDetect;
    }

    /**
     * 返回文件完整的web路径
     */
    public function fileWebpath($path)
    {
        $path = rtrim(config('report.reports.webpath'), '/') . '/' . ltrim($path, '/');
        return url($path);
    }
}
