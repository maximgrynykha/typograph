<?php


namespace Ismaxim\Typograph\Commands;

use Ismaxim\Typograph\Text;
use Ismaxim\Typograph\Font;
use Ismaxim\Typograph\Color;
use Ismaxim\Typograph\Width;
use Ismaxim\Typograph\Height;
use Intervention\Image\Image;
use Intervention\Image\AbstractFont;
use Intervention\Image\ImageManager;
use Ismaxim\Pathfinder\Path;

final class GenerateBookCoverCommand
{
    private string $cover_layouts_path;
    private string $cover_storage_path;

    public function __construct(
        ?string $cover_layouts_path = null, 
        ?string $cover_storage_path = null
    ) {
        $this->cover_layouts_path = $cover_layouts_path ?? "public/spin/";
        $this->cover_storage_path = $cover_storage_path ?? "public/covers/";
    }

    /**
     * @param Text $book_author
     * @param Text $book_name
     * @param Text $book_isbn
     * 
     * @return void
     */
    public function execute(Text $book_author, Text $book_name, Text $book_isbn): void
    {
        $image_manager = new ImageManager();
        $image = $image_manager->make($this->getRandomCoverLayout());

        $image->text($book_author->getText(), 60, 555, 
            static function (AbstractFont $font) use ($book_author): void {
                $font->file($book_author->getFont()->getFile());
                $font->color($book_author->getFill()->getColor());
                $font->size($book_author->getFont()->getSize());
            }
        );

        $base_spacing = 130; # Spacing between author name and book name

        $book_name_primary_font_size = 100;
        $book_name_scaled_font_size = $book_name->getFont()->getSize();

        $scaled_spacing = $base_spacing + $book_name_scaled_font_size - $book_name_primary_font_size;
        $spacing = ($book_author->getHeight()->getValue() - ($book_author->getFont()->getSize() / 3)) + $scaled_spacing;

        $image->text($book_name->getText(), 60, 555 + $spacing, 
            static function (AbstractFont $font) use ($book_name): void {
                $font->file($book_name->getFont()->getFile());
                $font->color($book_name->getFill()->getColor());
                $font->size($book_name->getFont()->getSize());
            }
        );

        $image->text($book_isbn->getText(), 365, 1887, 
            static function (AbstractFont $font) use ($book_isbn): void {
                $font->file($book_isbn->getFont()->getFile());
                $font->color($book_isbn->getFill()->getColor());
                $font->size($book_isbn->getFont()->getSize());
            }
        );

        // echo $image->response("png", 100);
        $image->save(Path::to("{$this->cover_storage_path}/{$book_isbn->getText()}.png"), 100);
    }

    /**
     * @return string
     */
    private function getRandomCoverLayout(): string
    {
        $cover_layouts = glob(Path::to($this->cover_layouts_path)."*.png");

        return collect($cover_layouts)->shuffle()->random();
    }
}