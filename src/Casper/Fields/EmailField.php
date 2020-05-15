<?php


namespace Casper\Fields;


class EmailField extends Fields
{
    /**
     * @param string $name
     * @return string
     */
    protected function asHtml(string $name): string
    {
        $res = parent::asHtml($name);
        $res = str_replace("type='text'", "type='email'", $res);
        return $res;
    }
}