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



}