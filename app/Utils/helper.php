<?php

use App\Models\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

if (!function_exists('isActive')) {
    function isActive($url)
    {
        return Route::is($url) ? "active" : "";
    }
}

if (!function_exists('isCollapse')) {
    function isCollapse($menus)
    {
        $active = '';
        foreach ($menus as $child) {
            if (isset($child['url'])) {
                if (Route::is($child['url'])) {
                    $active = "show active";
                }
            }
        }
        return $active;
    }
}

if (!function_exists('setting')) {
    function setting($name)
    {
        $setting = \Illuminate\Support\Facades\Cache::remember('setting_' . $name, now()->addMinutes(60), function () use ($name) {
            return Setting::where('name', $name)->first();
        });

        if ($setting) {
            return $setting->value;
        }

        return null;
    }
}

if (!function_exists('translator')) {
    function remove_invalid_charcaters($str)
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', $str);
    }

    function translator(string $key): string
    {
        try {
            $local = app()->getLocale();
            $langPath = resource_path('lang/' . $local . '/');
            if (!file_exists($langPath)) {
                mkdir($langPath, 0777, true);
            }
            $parts = explode('.', $key);
            if (count($parts) === 2) {
                $file = base_path("resources/lang/{$local}/{$parts[0]}.json");
                $translationKey = strtolower(str_replace([' ', '/', '', '-'], '_', $parts[1]));
                return putContent($file, $translationKey);
            } else {
                $file = base_path("resources/lang/{$local}/{$local}.json");
                $translationKey = strtolower(str_replace([' ', '/', '', '-'], '_', $key));
                return putContent($file, $translationKey);
            }
            return $key;
        } catch (\Exception $exception) {
            return $key;
        }
    }
}

function putContent($file, $translationKey)
{
    if (!File::exists($file)) {
        File::put($file, '{}');
    }

    $translations = json_decode(File::get($file), true);
    if (!isset($translations[$translationKey])) {
        $translations[$translationKey] = ucwords(str_replace(['/', ' ', '-', '_'], ' ', $translationKey));
        File::put($file, json_encode($translations, JSON_PRETTY_PRINT));
    }
    return $translations[$translationKey];
}

if (!function_exists('formatPrice')) {
    function formatPrice($amount, $decimals = 2)
    {
        $currency = \setting('currency_symbol') ?? '$';
        return $currency . '' . number_format($amount, $decimals);
    }
}

if (!function_exists('calculateDiscountAmount')) {
    function calculateDiscountAmount($subtotal, $discount, $discountType)
    {
        if ($discountType === 'percent') {
            return $subtotal * ($discount / 100);
        } elseif ($discountType === 'fixed') {
            return $discount;
        }
        return 0;
    }
}


if (!function_exists('calculatePercentage')) {
    function calculatePercentage($discount, $discountType)
    {
        if ($discountType === 'percent') {
            return $discount * ($discount / 100);
        } elseif ($discountType === 'fixed') {
            return $discount;
        }
        return 0;
    }
}


if (!function_exists('globalAsset')) {
    function globalAsset($url = null)
    {
        $path = 'storage/' . $url;
        if (!empty($url) && file_exists($path)) {
            return asset($path);
        }
        return asset('images/no-image.png');
    }
}


if (!function_exists('getCurrentLocale')) {
    function getCurrentLocale($localy = 'en', $field = 'icon')
    {
        $lang = [];
        foreach (\App\Utils\Utilites::languages() as $language) {
            if ($language['lang'] == $localy) {
                $lang = $language;
            }
        }
        return $lang[$field];
    }
}

if (!function_exists('roleName')) {
    function roleName()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user->roles->pluck('display_name')[0] ?? '';
    }
}


if (!function_exists('showAction')) {
    function showAction(string $model, $class = 'btn-outline-warning', $icon = 'bi bi-eye')
    {
        if (Auth::user()->can($model . '.show')) {
            return ['link' => (fn($item) => route($model . '.show', $item['id'])), 'icon' => $icon, 'class' => $class];
        }
        return [];
    }
}

if (!function_exists('editAction')) {
    function editAction(string $model, $class = 'btn-outline-primary', $icon = 'bi bi-pencil')
    {
        if (Auth::user()->can($model . '.edit')) {
            return ['link' => fn($item) => route($model . '.edit', $item['id']), 'class' => $class, 'icon' => $icon];
        }
        return null;
    }
}

if (!function_exists('deleteAction')) {
    function deleteAction($model, $class = null, $icon = 'bi bi-trash', $method = 'delete')
    {
        if (Auth::user()->can($model . '.destroy')) {
            return ['link' => fn($item) => route($model . '.destroy', $item['id']), 'icon' => $icon, 'class' => $class, 'method' => $method];
        }
        return [];
    }
}

if (!function_exists('extraAction')) {
    function extraAction(string $routeName, $peram = null, $class = 'btn-outline-primary', $icon = 'bi bi-list')
    {
        if (Auth::user()->can($routeName)) {
            return ['link' => (fn($item) => route($routeName, $item[$peram])), 'class' => $class, 'icon' => $icon];
        }
        return [];
    }
}
