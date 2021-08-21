<?php

namespace Ismaxim\Typograph;

use Ismaxim\Typograph\Enums\Units;

final class Font
{
    // Storage of font files
    public static string $storage;

    private ?string $file;
    private ?float $size;
    private ?float $leading;
    private Units $units;

    public function __construct() 
    {
        $this->file = null;
        $this->size = null;
        $this->leading = null;
        $this->units = Units::points();
    }

    /**
     * @param string $file
     * 
     * @return void
     */
    public function setFile(string $file): void
    {
        $this->file = $file;
    }

    /**
     * @param int $size
     * @param Units|null $units By default set size in points.
     * 
     * @return void
     */
    public function setSize(int $size, ?Units $units = null): void
    {
        if ($units === Units::points() && $this->units === Units::pixels()) {
            $size = self::pointsToPixels($size);
        } elseif ($units === Units::pixels() && $this->units === Units::points()) {
            $size = self::pixelsToPoints($size);
        }

        // if ($units === Units::points()) {
        //     $size = ($this->units === Units::points())
        //         ? $size
        //         : self::pointsToPixels($size);
        // } elseif ($units === Units::pixels()) {
        //     $size = ($this->units === Units::pixels())
        //         ? $size
        //         : self::pixelsToPoints($size);
        // }

        $this->size = $size;        
    }

    /**
     * @param float $leading
     * 
     * @return void
     */
    public function setLeading(float $leading): void
    {
        $this->leading = $leading;
    }

    /**
     * @param UnitsType $units
     * 
     * @return void
     */
    public function setUnits(Units $units): void
    {
        $this->units = $units;
    }

    /**
     * @return string|null
     */
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * @param Units|null $units By default return size in points.
     * 
     * @return float|null
     */
    public function getSize(?Units $units = null): ?float
    {
        if ($units === Units::points() && $this->units === Units::pixels()) {
            $size = self::pixelsToPoints($this->size);
        } elseif ($units === Units::pixels() && $this->units === Units::points()) {
            $size = self::pointsToPixels($this->size);
        }

        return $size ?? $this->size;

        // if ($units === Units::points()) {
        //     return ($this->units === Units::points())
        //         ? $this->size
        //         : self::pointsToPixels($this->size);
        // } elseif ($units === Units::pixels()) {
        //     return ($this->units === Units::pixels())
        //         ? $this->size
        //         : self::pixelsToPoints($this->size);
        // }

        // return $this->size;
    }

    /**
     * @return float|null
     */
    public function getLeading(): ?float
    {
        return $this->leading;
    }

    /**
     * @return string
     */
    public function getUnits(): string
    {
        return $this->units->value;
    }

    /**
     * Convert points to pixels.
     * 
     * @param float $points
     * 
     * @return float
     */
    public static function pointsToPixels(float $points): float
    {
        return $points / 0.75;
    }

    /**
     * Convert pixels to points.
     * 
     * @param float $pixels
     * 
     * @return float
     */
    public static function pixelsToPoints(float $pixels): float
    {
        return $pixels * 0.75;
    }

    /**
     * Scale font size to fit a text into max-height
     * of the allowed text container
     * 
     * @param int $font_size
     * @param int $max_boundaries
     * @param int $text_boundaries
     * 
     * @return void
     */
    public function scaleByHeight(int $font_size, int $text_height, int $max_text_height): void
    {
        $font_scale_ratio = sqrt($max_text_height / $text_height);
        $this->size = $this->scaleSize($font_size, $font_scale_ratio);
    }

    /**
     * Scale font size to fit a text into max-width
     * of the allowed text container
     * 
     * @param int $font_size
     * @param int $text_width
     * @param int $max_text_width
     * 
     * @return void
     */
    public function scaleByWidth(int $font_size, int $text_width, int $max_text_width): void 
    {
        $font_scale_ratio = $max_text_width / $text_width;
        $this->size = $this->scaleSize($font_size, $font_scale_ratio);
    }

    /**
     * Scale font size to fit a text into max-sizes 
     * of the allowed text container
     * 
     * @param int $font_size
     * @param float $font_scale_ratio
     * 
     * @return int
     */
    private function scaleSize(int $font_size, float $font_scale_ratio): int
    {
        return $font_size * $font_scale_ratio;
    }
}