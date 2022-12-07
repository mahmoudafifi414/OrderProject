<?php

namespace Package\Domain\ValueObject;

abstract class Identifier implements \JsonSerializable
{
    /** @var int */
    private int $value;

    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return  int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return  static
     */
    public static function of(int $value): static
    {
        return new static($value);
    }

    public function jsonSerialize(): int
    {
        return $this->value;
    }
}
