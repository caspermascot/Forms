<?php


namespace Casper\Fields;


class ChoiceField extends Fields
{
    /**
     * @var array
     */
    protected array $choices;

    /**
     * @var bool
     */
    protected bool $multiple;
    /**
     * @var string
     */
    protected string $delimiter='&';

    /**
     * @param array $choices
     * @return $this
     */
    public function choices(array $choices): self
    {
        $this->choices = $choices;
        return $this;
    }

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