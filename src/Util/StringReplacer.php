<?php

namespace App\Util;

class StringReplacer
{
    /**
     * @param array $variables
     * @param string[]|array $staticReplaces
     * @param callable[]|array $dynamicReplaces
     *
     * @return array
     */
    public static function recursiveReplace(
        array $variables,
        array $staticReplaces = [],
        array $dynamicReplaces = []
    ): array {
        foreach ($variables as $key => &$value) {
            if (is_string($value)) {
                $value = static::replaceString($value, $staticReplaces);
            } elseif (is_int($value)) {
                continue;
            } elseif (is_array($value)) {
                $value = static::recursiveReplace($value, $staticReplaces);
            } else {
                throw new \InvalidArgumentException(sprintf(
                    'Invalid value. string/array allowed, %s (%s) given.',
                    gettype($value),
                    print_r($value, true)
                ));
            }
        }

        return $variables;
    }

    private static function replaceString(
        string $string,
        array $staticReplaces
    ): string {
        // Process static replaces
        $string = str_replace(
            array_keys($staticReplaces),
            array_values($staticReplaces),
            $string
        );

        // TBI
        // ...

        return $string;
    }
}
