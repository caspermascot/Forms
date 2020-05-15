<?php


namespace Casper\Fields;


class RadioField extends Fields
{
    /**
     * @var array
     */
    protected array $choices;

    /**
     * @param array $choices
     * @return $this
     */
    public function choices(array $choices): self
    {
        $this->choices = $choices;
        return $this;
    }
}