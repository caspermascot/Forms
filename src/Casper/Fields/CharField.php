<?php


namespace Casper\Fields;


class CharField extends Fields
{
    protected int $minLength;
    protected int $maxLength;

    public function minLength(int $minLength): self
    {
        $this->minLength = $minLength;
        return $this;
    }

    public function maxLength(int $maxLength): self
    {
        $this->maxLength = $maxLength;
        return $this;
    }
}