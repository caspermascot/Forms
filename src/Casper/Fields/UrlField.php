<?php


namespace Casper\Fields;


class UrlField extends CharField
{
    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $res = str_replace("type='text'", "type='url'", $res);
        return $res;
    }
}