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
     * @var string
     */
    protected string $src;

    /**
     * @var bool
     */
    protected bool $multiple = false;

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
     * @param string $src
     * @return $this
     */
    public function src(string $src): self
    {
        $this->src = $src;
        return $this;
    }

    /**
     * @param bool $multiple
     * @return $this
     */
    public function multiple(bool $multiple): self
    {
        $this->multiple = $multiple;
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
        $res['multiple'] = $this->getProperty('multiple');
        return $res;
    }

    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $replacement = "type='file' ";
        if(!empty($this->src)){
            $replacement .= "src='{$this->src}' ";
        }

        if(!empty($this->multiple)){
            $replacement .= "multiple='true' ";
        }


        $res = parent::asHtml($name);
        $res = str_replace("type='text'", $replacement, $res);

        if(!empty($this->multiple)){
            $res = str_replace(" name='{$name}' ", " name='{$name}[]' ", $res);
        }
        return $res;
    }
}