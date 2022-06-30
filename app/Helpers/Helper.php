<?php
use Illuminate\Support\Facades\Storage;

if (!function_exists('active_menu')) {
    function active_menu($routeName = [], $option = null)
    {
        if (in_array(\Route::currentRouteName(), $routeName)) {
            return $option ?? 'active';
        }

        return '';
    }
}

if (!function_exists('_empty')) {
    function _empty($name)
    {
        return $name;
    }
}

if (!function_exists('copy_image')) {
    function copy_image($url = null, $folder = null)
    {
        if ($url) {
            $url = str_replace(' ', '%20', $url);
            $path = $folder . '/' . basename($url);
            if (!Storage::exists($path)) {
                Storage::put($path, file_get_contents($url));
            }

            return $path;
        }

        return null;
    }
}

if (!function_exists('img_path')) {
    function img_path($path)
    {
        if ($path) {
            if (!Storage::exists($path)) {
                return config('app.url') . '/images/no_image.png';
            }
    
            return Storage::url($path);
        }
        
        return '';
    }
}