<?php


namespace Casper\Fields;


class TextField extends Fields
{
    /**
     * @var int
     */
    protected int $cols;
    /**
     * @var int
     */
    protected int $rows;

    /**
     * @param int $cols
     * @return $this
     */
    public function cols(int $cols): self
    {
        $this->cols = $cols;
        return  $this;
    }

    /**
     * @param int $rows
     * @return $this
     */
    public function rows(int $rows): self
    {
        $this->rows = $rows;
        return $this;
    }

    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['cols'] = $this->getProperty('cols');
        $res['rows'] = $this->getProperty('rows');
        return $res;
    }
}