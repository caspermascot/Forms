<?php


namespace Casper\Fields;


use Casper\FormUtils;

class SlugField extends CharField
{
    /**
     * @param $data
     * @return Fields
     */
    public function setCleanedData($data): Fields
    {
        return parent::setCleanedData(FormUtils::slugify($this->data));
    }
}