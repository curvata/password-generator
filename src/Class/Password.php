<?php

namespace App\Class;

class Password
{
    private int $int = 0;
    private int $upper = 0;
    private int $length = 5;
    private int $symbol = 0;
    private int $many = 1;
    
    public function getInt(): int
    {
        return $this->int;
    }

    public function setInt(int $int): self
    {
        $this->int = $int;

        return $this;
    }

    public function getUpper(): int
    {
        return $this->upper;
    }

    public function setUpper(int $upper): self
    {
        $this->upper = $upper;

        return $this;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getSymbol(): int
    {
        return $this->symbol;
    }

    public function setSymbol(int $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getMany(): int
    {
        return $this->many;
    }

    public function setMany(int $many): self
    {
        $this->many = $many;

        return $this;
    }
}
