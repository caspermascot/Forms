<?php


namespace Casper\Fields;


class PasswordField extends CharField
{
    protected bool $upper;
    protected bool $lower;
    protected bool $number;
    protected bool $symbol;

    /**
     * @param bool $upper
     * @return $this
     */
    public function mustContainUpperCase(bool $upper): self
    {
        $this->upper = $upper;
        return $this;
    }

    /**
     * @param bool $lower
     * @return $this
     */
    public function mustContainLowerCase(bool $lower): self
    {
        $this->lower = $lower;
        return $this;
    }

    /**
     * @param bool $number
     * @return $this
     */
    public function mustContainNumber(bool $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @param bool $symbol
     * @return $this
     */
    public function mustContainSymbol(bool $symbol): self
    {
        $this->symbol = $symbol;
        return $this;
    }

}