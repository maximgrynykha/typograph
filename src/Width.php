<?php

namespace Ismaxim\Typograph;

use Ismaxim\Typograph\Enums\Units;

final class Width extends Length 
{
    /**
     * @param string $text
     * @param Font $font
     * 
     * @return int
     */
    public static function getForText(string $text, Font $font): int
    {
        // https://www.php.net/manual/en/function.imageftbbox.php#refsect1-function.imageftbbox-returnvalues
        if ($font->getUnits() === Units::pixels()->value) {
            $bbox = imageftbbox($font->getSize(), 0, $font->getFile(), " " . $text);
        } elseif ($font->getUnits() === Units::points()->value) {
            $bbox = imageftbbox(Font::pixelsToPoints($font->getSize()), 0, $font->getFile(), " " . $text);
        }

        return $bbox[2] - $bbox[0];
    }
}