<?php


namespace Casper\Fields;


class ColorField extends Fields
{
    /**
     * @param string $name
     * @return string
     */
    protected function asHtml(string $name=''): string
    {
        $replacement = "type='color' ";
        $res = parent::asHtml($name);
        $res = str_replace("type='text'", $replacement, $res);
        return $res;
    }
}