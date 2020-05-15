<?php


namespace Casper\Fields;


class ImageField extends FileField
{
    /**
     * @var int
     */
    protected int $height;
    /**
     * @var int
     */
    protected int $width;
    /**
     * @var string
     */
    protected string $accepts = 'image/*';


    /**
     * @param int $height
     * @return ImageField
     */
    public function height(int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param int $width
     * @return ImageField
     */
    public function width(int $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param string $accepts
     * @return ImageField
     */
    public function accepts(string $accepts): self
    {
        $this->accepts = $accepts;
        return $this;
    }
}