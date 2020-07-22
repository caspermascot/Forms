<?php


namespace Casper\Fields;


class PhoneField extends CharField
{

    protected bool $internationalize;

    /**
     * @param bool $internationalize
     * @return $this
     */
    public function internationalFormat(bool $internationalize): self
    {
        $this->internationalize = $internationalize;
        return $this;
    }

    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['internationalize'] = !empty($this->getProperty('internationalize'));
        return $res;
    }

    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $res = str_replace("type='text'", "type='tel'", $res);
        return $res;
    }
}