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
}