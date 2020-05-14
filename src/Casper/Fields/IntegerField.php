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
     * @var int
     */
    protected int $step;

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
     * @param int $step
     * @return IntegerField
     */
    public function step(int $step): self
    {
        $this->step = $step;
        return $this;
    }



}