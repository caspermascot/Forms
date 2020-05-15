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
     * @var string
     */
    protected string $alt;


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
        if(in_array(strtolower($accepts),['file_extension','audio/*','video/*','image/*','media_type'])){
            $this->accepts = strtolower($accepts);
        }
        return $this;
    }

    /**
     * @param string $alt
     * @return $this
     */
    public function alt(string $alt): self
    {
        $this->alt = $alt;
        return $this;
    }

    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['height'] = $this->getProperty('height');
        $res['width']  = $this->getProperty('width');
        $res['accepts'] = $this->getProperty('accepts');
        $res['alt'] = $this->getProperty('alt');
        return $res;
    }
    /**
     * @param string $name
     * @return string
     */
    protected function asHtml(string $name): string
    {
        $replacement = "type='image' ";
        $res = parent::asHtml($name);
        $res = str_replace("type='file'", $replacement, $res);
        return $res;
    }
}