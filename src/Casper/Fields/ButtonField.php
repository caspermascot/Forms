<?php


namespace Casper\Fields;


use Casper\Exceptions\InvalidButtonTypeException;

class ButtonField extends BaseField
{
    /**
     * @var string
     */
    protected string $type = 'submit';
    /**
     * @var string
     */
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

    /**
     * @param string $name
     * @param bool $modify
     * @return string
     */
    protected function asHtml(string $name, bool $modify = true): string
    {
        $res = parent::asHtml($name);
        $res = str_replace('htmlLabel', '', $res);
        $field = " <br> <input class='{$this->getProperty('style')}' type='{$this->getProperty('type')}' value='{$this->getProperty('label')}'> ";
        $res = str_replace('htmlField', $field, $res);
        return $res;
    }

    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['type'] = $this->getProperty('type');

        return $res;
    }
}