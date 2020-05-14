<?php


namespace Casper\Fields;


use Casper\Exceptions\InvalidButtonTypeException;

class ButtonField extends BaseField
{
    protected string $type = 'submit';
    protected string $style;

    /**
     * @param string $type
     * @return $this
     * @throws InvalidButtonTypeException
     */
    public function type(string $type): self
    {
        if(!in_array(strtolower($type),['submit', 'reset']))
            throw new InvalidButtonTypeException();

        $this->type = $type;
        return $this;
    }
}