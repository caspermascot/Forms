<?php


namespace Casper\Fields;


class DateTimeField extends DateField
{
    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $res = str_replace("type='date'", "type='datetime-local'", $res);
        return $res;
    }

    /**
     * @return array
     */
    public function asJsonSchema(): array
    {
        $res = parent::asJsonSchema();
        $res['format'] = 'date-time';

        return $res;
    }

    public function setCleanedData($data): Fields
    {
        return parent::setCleanedData(date('c', strtotime($this->data)));
    }
}