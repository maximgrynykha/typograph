<?php

namespace Ismaxim\Typograph;

final class Color
{
    private ?string $value; // hex

    public function __construct()
    {
        $this->value = null;
    }

    /**
     * @param string $value
     * 
     * @return void
     */
    public function setColor(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->value;
    }
}