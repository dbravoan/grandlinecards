<?php
declare(strict_types=1);
namespace GrandLineCards\Shared\Domain;

final class Utils
{
    public static function endsWith(string $needle, string $haystack): bool
    {
        return str_ends_with($haystack, $needle);
    }

    public static function dateToString(\DateTimeInterface $date): string
    {
        return $date->format(\DateTimeInterface::ATOM);
    }

    public static function jsonEncode(array $data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    public static function jsonDecode(string $json): array
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }

    public static function toSnakeCase(string $text): string
    {
        return ctype_lower($text) ? $text : strtolower(preg_replace('/([^A-Z\s])([A-Z])/', "$1_$2", $text));
    }

    public static function toCamelCase(string $text): string
    {
        return lcfirst(str_replace('_', '', ucwords($text, '_')));
    }

    public static function dot(array $array, string $prepend = ''): array
    {
        $results = [];
        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, self::dot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }
        return $results;
    }
}
