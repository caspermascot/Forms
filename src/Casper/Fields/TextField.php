<?php


namespace Casper\Fields;


class TextField extends Fields
{

    protected int $cols;
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
}