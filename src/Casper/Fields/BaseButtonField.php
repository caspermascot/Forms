<?php


namespace Casper\Fields;


use Casper\Exceptions\InvalidButtonTypeException;

class BaseButtonField extends BaseField
{
    /**
     * @var string
     */
    protected string $type = 'submit';
    /**
     * @var string|null
     */
    protected ?string $style;

    /**
     * @param string $type
     * @return $this
     * @throws InvalidButtonTypeException
     */
    public function type(string $type): self
    {
        if(!in_array(strtolower($type),['submit','reset','button'])){
            throw new InvalidButtonTypeException();
        }

        $this->type = $type;
        return $this;
    }

    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $res = str_replace(array('htmlLabel', 'helpText'), '', $res);
        $field = " <br> <input 
                            class='{$this->getProperty('style')}' 
                            type='{$this->getProperty('type')}' 
                            value='{$this->getProperty('label')}'> ";
        $res = str_replace(array('htmlField', '<br>'), array($field, ''), $res);
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