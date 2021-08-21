<?php

namespace Ismaxim\Typograph\Support;

final class StringUtils
{
    /**
     * Replaces the first occurrence of $needle from $haystack 
     * with $replace and returns the resultant string.
     * 
     * @param string $haystack
     * @param string $needle
     * @param string $replace
     * 
     * @return string
     */
    public static function replaceFirstOccurence(string $haystack, string $needle, string $replace): string 
    {
        // reference: https://stackoverflow.com/a/1252710/3679900
        $pos = strpos($haystack, $needle);

        if ($pos !== false) {
            $new_string = substr_replace($haystack, $replace, $pos, strlen($needle));
        }

        return $new_string;
    }

    /**
     * Removes the first occurrence $needle from $haystack 
     * and returns the resulting string.
     * 
     * @param string $haystack
     * @param string $needle
     * 
     * @return string
     */
    public static function removeFirstOccurrence(string $haystack, string $needle): string 
    {
        return self::replaceFirstOccurence($haystack, $needle, '');
    }
}