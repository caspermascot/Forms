<?php


namespace Casper\Fields;


class PasswordField extends CharField
{
    /**
     * @var bool
     */
    protected bool $upper;
    /**
     * @var bool
     */
    protected bool $lower;
    /**
     * @var bool
     */
    protected bool $number;
    /**
     * @var bool
     */
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

    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['upper'] = $this->getProperty('upper');
        $res['lower'] = $this->getProperty('lower');
        $res['symbol'] = $this->getProperty('symbol');
        $res['number'] = $this->getProperty('number');

        return $res;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $res = str_replace("type='text'", "type='password'", $res);
        return $res;
    }
}