<?php


namespace Casper\Fields;


class FileField extends Fields
{
    /**
     * @var int
     */
    protected int $minSize;
    /**
     * @var int
     */
    protected int $maxSize;
    /**
     * @var array
     */
    protected array $type;

    /**
     * @param int $minSize
     * @return FileField
     */
    public function minSize(int $minSize): self
    {
        $this->minSize = $minSize;
        return $this;
    }

    /**
     * @param int $maxSize
     * @return FileField
     */
    public function maxSize(int $maxSize): self
    {
        $this->maxSize = $maxSize;
        return $this;
    }

    /**
     * @param array $type
     * @return FileField
     */
    public function type(array $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['minSize'] = $this->getProperty('minSize');
        $res['maxSize'] = $this->getProperty('maxSize');
        $res['type']    = $this->getProperty('type');
        return $res;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function asHtml(string $name): string
    {
        $replacement = "type='file' ";
        $res = parent::asHtml($name);
        $res = str_replace("type='text'", $replacement, $res);
        return $res;
    }
}