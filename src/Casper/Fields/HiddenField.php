<?php


namespace Casper\Fields;


class HiddenField extends CharField
{
    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $label = ucfirst($this->getProperty('label'));
        $labelReplacement = "<label class='{$this->getProperty('style')}' for='{$name}'>{$label}</label> <br> ";
        $res = str_replace("type='text'", "type='hidden'", $res);
        $res = str_replace([$labelReplacement,'<br>'], '', $res);
        return $res;
    }
}