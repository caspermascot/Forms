<?php


namespace Casper\Fields;


class TimeField extends DateField
{
    /**
     * @param string $name
     * @return string
     */
    protected function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $res = str_replace("type='date'", "type='time'", $res);
        return $res;
    }

    /**
     * @return array
     */
    public function asJsonSchema(): array
    {
        $res = parent::asJsonSchema();
        $res['format'] = 'time';

        return $res;
    }
}