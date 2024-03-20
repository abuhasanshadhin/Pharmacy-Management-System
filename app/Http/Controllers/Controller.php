<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * Storage url
     *
     * @param string $path
     * @return string
     */
    public static function storage_url($path = '/')
    {
        return sprintf('%s/%s', url('storage'), $path);
    }
    /**
     * Upload file
     *
     * @param Illuminate\Http\UploadedFile $file
     * @param string $destination
     * @param string|null $prev_path
     * @return string|null
     */
    public static function upload($file, $destination, $prev_path = null)
    {
        $name = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();

        $name = pathinfo($name, PATHINFO_FILENAME);
        $name = Str::of($name)->replace(['_', ' '], '-')->lower();

        $name = sprintf('%s-%s.%s', $name, uniqid(), $ext);
        $path = sprintf('%s/%s', date('Y/m/d'), $destination);
        $filePath = sprintf('%s/%s', $path, $name);
        $uploaded = Storage::disk('public')->putFileAs($path, $file, $name);

        if (!empty($prev_path) && Storage::disk('public')->exists($prev_path)) {
            Storage::disk('public')->delete($prev_path);
        }

        return $uploaded ? $filePath : null;
    }
}
