<?php


namespace Casper\Fields;


class FloatField extends IntegerField
{

    /**
     * @param $data
     * @return IntegerField
     */
    public function setCleanedData($data): IntegerField
    {
        return parent::setCleanedData((float)$data);
    }
}