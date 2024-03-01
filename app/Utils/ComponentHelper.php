<?php

namespace App\Utils;

class ComponentHelper
{
    public static function htmlInputNameArrayToDots(string $name): string
    {
        if (str_contains($name, '[')) {
            return str_replace(['[', ']'], ['.', ''], $name);
        }

        return $name;
    }

    public static function htmlTagAttrsArrayToString(array $attrs): string
    {
        if (count($attrs) > 0) {
            return collect($attrs)
                ->map(function ($value, $key) {
                    return is_int($key) ? $value : sprintf('%s="%s"', $key, $value);
                })
                ->join(' ');
        }

        return '';
    }

    public static function tableCellText(array|object $item, array $header): string
    {
        if (!isset($header['value'])) {
            return '';
        } elseif (is_callable($header['value'])) {
            return $header['value']($item);
        } elseif (is_object($item) || is_array($item)) {
            return data_get($item, $header['value']);
        } else {
            return $header['value'];
        }
    }

    public static function tableActionLink(array|object $item, array $action): string
    {
        if (!isset($action['link'])) {
            return '';
        } elseif (is_callable($action['link'])) {
            return $action['link']($item);
        } else {
            return $action['link'];
        }
    }
}