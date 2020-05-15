<?php


namespace Casper\Fields;


class ChoiceField extends RadioField
{
    /**
     * @var bool
     */
    protected bool $multiple;

    /**
     * @param bool $multiple
     * @return $this
     */
    public function multiple(bool $multiple): self
    {
        $this->multiple = $multiple;
        return $this;
    }
}