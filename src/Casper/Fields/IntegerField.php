<?php


namespace Casper\Fields;


class IntegerField extends Fields
{
    /**
     * @var int
     */
    protected int $minValue;
    /**
     * @var int
     */
    protected int $maxValue;
    /**
     * @var float
     */
    protected float $step;

    /**
     * @param int $minValue
     * @return IntegerField
     */
    public function minValue(int $minValue): self
    {
        $this->minValue = $minValue;
        return $this;
    }

    /**
     * @param int $maxValue
     * @return IntegerField
     */
    public function maxValue(int $maxValue): self
    {
        $this->maxValue = $maxValue;
        return $this;
    }

    /**
     * @param float $step
     * @return IntegerField
     */
    public function step(float $step = 0): self
    {
        $this->step = $step;
        return $this;
    }

    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['minValue'] = $this->getProperty('minValue');
        $res['maxValue'] = $this->getProperty('maxValue');
        $res['step'] = $this->getProperty('step');
        return $res;
    }

    /**
     * @return array
     */
    public function asJsonSchema(): array
    {
        $res = parent::asJsonSchema();
        $res['type'] = 'number';

        if(!empty($this->getProperty('minValue'))){
            $res['minimum'] = $this->getProperty('minValue');
        }

        if(!empty($this->getProperty('maxValue'))){
            $res['maximum'] = $this->getProperty('maxValue');
        }
        return $res;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function asHtml(string $name=''): string
    {
        $replacement = "type='number' ";
        if(!empty($this->minValue)){
            $replacement .= "min='{$this->minValue}' ";
        }

        if(!empty($this->maxLength)){
            $replacement .= "max='{$this->maxValue}' ";
        }

        if(!empty($this->step)){
            $replacement .= "step='{$this->step}' ";
        }

        $res = parent::asHtml($name);
        $res = str_replace("type='text'", $replacement, $res);
        return $res;
    }

}