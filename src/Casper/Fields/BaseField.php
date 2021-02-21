<?php


namespace Casper\Fields;


use Casper\Exceptions\FieldCreateFailedException;
use Casper\FormUtils;

class BaseField
{
    public const MESSAGE = 'Cannot set required to true when a default is provided, On field %s of form %s ';

    /**
     * @var string|null
     */
    protected ?string $label;
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string|null
     */
    protected ?string $style;
    /**
     * @param $name
     * @param $arguments
     * @return mixed|null
     */
    public function __call($name, $arguments)
    {
        if(method_exists($this, $name)){
            return call_user_func_array([$this, $name], $arguments);
        }

        return null;
    }

    /**
     * @param string $property
     * @return mixed|null
     */
    public function getProperty(string $property)
    {
        if(property_exists($this, $property)){
            return $this->$property ?? null;
        }
        return null;
    }

    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name = ''): string
    {
        if(empty($name)){
            $this->name = (string) $this->getProperty('name');
        }
        return " <div class='{$this->getProperty('style')}'> <br> <span> htmlLabel htmlField helpText </span> </div> ";
    }

    /**
     * @param string $style
     * @return self
     */
    public function style(string $style): self
    {
        $this->style = $style;
        return $this;
    }
    /**
     * @param string $label
     * @return self
     */
    public function label(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    protected function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function asJson(): array
    {
        return [
            'type' => FormUtils::getFieldType($this),
            'label' => $this->getProperty('label'),
            'name' => $this->getProperty('name'),
            'style' => $this->getProperty('style')
        ];
    }

    /**
     * @param string $caller
     * @throws FieldCreateFailedException
     */
    protected function validateFieldCreate(string $caller): void
    {
        $name = $this->getProperty('name');

        if(($this instanceof Fields) && !empty($this->required) && !empty($this->default)) {
            throw new FieldCreateFailedException(sprintf(self::MESSAGE, $name, $caller));
        }

        if(empty($this->getProperty('label'))){
            $this->label = $name;
        }
    }
}