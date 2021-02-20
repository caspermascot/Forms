<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;

class ColorField extends Fields
{
    private const colorRegex = "/#([a-f0-9]{3}){1,2}\b/i";
    private const errorMessage = 'Invalid hex color';
    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $replacement = "type='color' ";
        $res = parent::asHtml($name);
        $res = str_replace("type='text'", $replacement, $res);
        return $res;
    }

    public function validate(): ?string
    {
        try {
//            $this->checkEmpty($this->data);
            $this->checkRequired($this->data);
            $this->checkNull($this->data);
            $this->checkBlank($this->data);

            if(!empty($this->data)){
                if(isset($this->regex)){
                    $this->checkRegex($this->regex, $this->data);
                }
                if(!preg_match(self::colorRegex, $this->data)){
                    throw new ValidationFailedException(self::errorMessage);
                }

                $this->setCleanedData((string) $this->data);
            }

            $this->isValid = true;
            return null;

        }catch (ValidationFailedException $validationFailedException){
            $this->isValid = false;
            return $validationFailedException->getMessage();
        }
    }
}