<?php


namespace Casper\Fields;


class BaseField
{

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
            return isset($this->$property) ? $this->$property : null;
        }
        return null;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function asHtml(string $name): string
    {
        return " {$name} ";
    }

    /**
     * @param string $name
     * @return string
     */
    protected function asTable(string $name): string
    {
        return " {$name} ";
    }

    /**
     * @param string $name
     * @return string
     */
    protected function asP(string $name): string
    {
        return " {$name} ";
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
}