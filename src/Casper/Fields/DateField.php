<?php


namespace Casper\Fields;


class DateField extends Fields
{

    /**
     * @var string
     */
    protected string $minValue;
    /**
     * @var string
     */
    protected string $maxValue;

    /**
     * @param string $minValue
     * @return DateField
     */
    public function minValue(string $minValue): self
    {
        $this->minValue = $minValue;
        return $this;
    }

    /**
     * @param string $maxValue
     * @return DateField
     */
    public function maxValue(string $maxValue): self
    {
        $this->maxValue = $maxValue;
        return $this;
    }
    /**
     * @param string $name
     * @return string
     */
    protected function asHtml(string $name): string
    {
        $replacement = "type='date' ";
        if(!empty($this->minValue)){
            $replacement .= "min='{$this->minValue}' ";
        }

        if(!empty($this->maxValue)){
            $replacement .= "max='{$this->maxValue}' ";
        }
        $res = parent::asHtml($name);
        $res = str_replace("type='text'", $replacement, $res);
        return $res;
    }
    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['minValue'] = $this->getProperty('minValue');
        $res['maxValue'] = $this->getProperty('maxValue');
        return $res;
    }
}