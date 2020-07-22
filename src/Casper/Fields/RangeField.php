<?php


namespace Casper\Fields;


class RangeField extends IntegerField
{
    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $res = str_replace("type='number'", "type='range'", $res);
        return $res;
    }
}