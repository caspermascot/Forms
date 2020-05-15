<?php


namespace Casper\Fields;


class CharField extends Fields
{
    /**
     * @var int
     */
    protected int $minLength;
    /**
     * @var int
     */
    protected int $maxLength;

    /**
     * @param int $minLength
     * @return $this
     */
    public function minLength(int $minLength): self
    {
        $this->minLength = $minLength;
        return $this;
    }

    /**
     * @param int $maxLength
     * @return $this
     */
    public function maxLength(int $maxLength): self
    {
        $this->maxLength = $maxLength;
        return $this;
    }
}