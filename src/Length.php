<?php

namespace Ismaxim\Typograph;

abstract class Length
{
    protected ?int $value;
    protected ?int $min_value;
    protected ?int $max_value;
    
    public function __construct() 
    {
        $this->value = null;
        $this->min_value = null;
        $this->max_value = null;
    }

    /**
     * @param int $value
     * 
     * @return void
     */
    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    /**
     * @param int $min_value
     * 
     * @return void
     */
    public function setMinValue(int $min_value): void
    {
        $this->min_value = $min_value;
    }

    /**
     * @param int $max_value
     * 
     * @return void
     */
    public function setMaxValue(int $max_value): void
    {
        $this->max_value = $max_value;
    }

    /**
     * @return int|null
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * @return int|null
     */
    public function getMinValue(): ?int
    {
        return $this->min_value;
    }

    /**
     * @return int|null
     */
    public function getMaxValue(): ?int
    {
        return $this->max_value;
    }
}