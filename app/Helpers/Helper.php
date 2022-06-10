<?php

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