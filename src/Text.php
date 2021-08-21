<?php

namespace Ismaxim\Typograph;

use Ismaxim\Typograph\Enums\Alignment;
use Ismaxim\Typograph\Support\StringUtils;

final class Text
{
    private string $text;

    private Font $font;
    private Color $fill;
    private Width $width;
    private Height $height;
    private Alignment $alignment;

    public function __construct(string $text) 
    {
        $this->text = $text;
        $this->font = new Font;
        $this->fill = new Color;
        $this->width = new Width;
        $this->height = new Height;
        $this->alignment = Alignment::left();
    }

    /**
     * @param \Closure $styles
     * 
     * @return void
     */
    public function setFont(\Closure $styles): void
    {
        $styles($this->font);
    }

    /**
     * @param \Closure $styles
     * 
     * @return void
     */
    public function setWidth(\Closure $styles): void
    {
        $styles($this->width);

        // Set text container width by default. 
        // Based on the width of text by itself.
        if (! $this->width->getValue()) {
            $this->width->setValue(
                Width::getForText(
                    $this->text,
                    $this->font
                )
            );
        }
    }

    /**
     * @param \Closure $styles
     * 
     * @return void
     */
    public function setHeight(\Closure $styles): void
    {
        $styles($this->height);

        // Set text container height by default. 
        // Based on the height of text by itself.
        if (! $this->height->getValue()) {
            $this->height->setValue(
                Height::getForText(
                    $this->text, 
                    $this->font
                )
            );
        }
    }

    /**
     * @param \Closure $styles
     * 
     * @return void
     */
    public function setFill(\Closure $styles): void
    {
        $styles($this->fill);
    }

    /**
     * @param Alignment $alignment
     * 
     * @return void
     */
    public function setAlignment(Alignment $alignment): void
    {
        $this->alignment = $alignment;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return Font
     */
    public function getFont(): Font
    {
        return clone $this->font;
    }

    /**
     * @return Color
     */
    public function getFill(): Color
    {
        return clone $this->fill;
    }

    /**
     * @return Width
     */
    public function getWidth(): Width
    {
        return clone $this->width;
    }

    /**
     * @return Height
     */
    public function getHeight(): Height
    {
        return clone $this->height;
    }

    /**
     * @return string
     */
    public function getAlignment(): string
    {
        return $this->alignment->value;
    }
    
    /**
     * ___Break___ text into lines according to the max-width of the text container  
     * and ___scale___ the font size if the text is too large to fit within  
     * the max-width and the max-height of the text container.
     * 
     * © The source code to extending was taken from the _[Github Gist](https://git.io/J0T6h)_
     * 
     * @return self
     */
    public function format(): self
    {
        $words = explode(' ', $this->text);
        $lines = [];

        $current_line_index = 0;
        $current_line_width = 0;

        foreach ($words as $index => $word) {
            $current_line_new_width = $current_line_width + Width::getForText(' ' . $word, $this->font);
            $current_word_width = Width::getForText($word, $this->font);

            if ($current_word_width > $this->width->getMaxValue()) {
                $this->font->scaleByWidth(
                    $this->font->getSize(),
                    $current_word_width,
                    $this->width->getMaxValue()
                );
                
                if ($current_line_width == 0) {
                    $lines[$current_line_index] = [$word];
                    $current_line_index++;
                } else {
                    $lines[++$current_line_index] = [$word];
                    $current_line_index++;
                    $current_line_width = 0;
                }

                return $this->format(); // recursion
            } elseif ($current_line_new_width > $this->width->getMaxValue()) {
                $lines[++$current_line_index] = [$word];
                $current_line_width = $current_word_width;
            } else {
                $lines[$current_line_index][] = $word;
                $current_line_width = $current_line_new_width;
            }
        }

        $output_text = array_reduce(
            $lines,
            static function (string $wrapped_text, array $current_line): string {
                return $wrapped_text . PHP_EOL . implode(' ', $current_line);
            },
            ''
        );
        
        $output_text = StringUtils::removeFirstOccurrence($output_text, PHP_EOL);

        $output_text_height = Height::getForText($output_text, $this->font);
        
        if ($output_text_height > $this->height->getMaxValue()) {
            $this->font->scaleByHeight(
                $this->font->getSize(),
                $output_text_height,
                $this->height->getMaxValue()
            );
            
            return $this->format(); // recursion
        }
        
        $this->text = $output_text;

        $this->width->setValue(Width::getForText($output_text, $this->font));
        $this->height->setValue(Height::getForText($output_text, $this->font));

        return $this;
    }
}