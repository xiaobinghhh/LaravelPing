<?php

namespace App\Services;

use Dflydev\ApacheMimeTypes\PhpRepository;
use Illuminate\Support\Facades\Storage;

class FinalExamUploadsManager extends FileUploadsManager
{
    /**
     * 构造函数，确定文件上传文件夹
     */
    public function __construct(PhpRepository $mimeDetect)
    {
        $this->disk = Storage::disk(config('final_exam.final_exams.storage'));
        $this->mimeDetect = $mimeDetect;
    }

    /**
     * 返回文件完整的web路径
     */
    public function fileWebpath($path)
    {
        $path = rtrim(config('final_exam.final_exams.webpath'), '/') . '/' . ltrim($path, '/');
        return url($path);
    }
}
