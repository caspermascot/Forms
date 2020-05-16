<?php


namespace Casper\Fields;


class CharField extends Fields
{
    /**
     * @var int
     */
    protected int $minLength;
    /**
     * @var int
     */
    protected int $maxLength;

    /**
     * @param int $minLength
     * @return $this
     */
    public function minLength(int $minLength): self
    {
        $this->minLength = $minLength;
        return $this;
    }

    /**
     * @param int $maxLength
     * @return $this
     */
    public function maxLength(int $maxLength): self
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['minLength'] = $this->getProperty('minLength');
        $res['maxLength'] = $this->getProperty('maxLength');
        return $res;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function asHtml(string $name=''): string
    {
        $replacement = "type='text' ";
        if(!empty($this->minLength)){
            $replacement .= "minlength='{$this->minLength}' ";
        }

        if(!empty($this->maxLength)){
            $replacement .= "maxlength='{$this->maxLength}' ";
        }
        $res = parent::asHtml($name);
        $res = str_replace("type='text'", $replacement, $res);
        return $res;
    }
}