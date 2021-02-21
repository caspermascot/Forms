<?php


namespace Casper\Fields;


use Casper\Exceptions\ValidationFailedException;

class FileField extends Fields
{
    private const fileSizeMinError = "Size cannot be less than %s mb";
    private const fileSizeMaxError = "Size cannot be greater than %s mb";
    private const fileErrorType = "Unsupported type given. Must be one of ( %s )";


    protected ?int $minSize;


    protected ?int $maxSize;


    protected ?array $type;
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

    public function validate(): ?string
    {
        try {
            $this->checkEmpty($this->data);
            $this->checkRequired($this->data);
            $this->checkNull($this->data);
            $this->checkBlank($this->data);

            if(!empty($this->data)){
                $this->minSize = $this->minSize ?? null;
                $this->maxSize = $this->maxSize ?? null;
                $this->type = $this->type ?? null;
                if($this->multiple === true){
                    $count = count($this->data['size']);
                    for($index = 0; $index < $count; ++$index) {
                        $data = [
                            'name' => $this->data['name'][$index],
                            'type' => $this->data['type'][$index],
                            'tmp_name' => $this->data['tmp_name'][$index],
                            'error' => $this->data['error'][$index],
                            'size' => $this->data['size'][$index],
                        ];
                        $this->validateFile($data,$this->minSize,$this->maxSize,$this->type);
                    }
                }else{
                    $this->validateFile($this->data, $this->minSize, $this->maxSize, $this->type);
                }
                $this->setCleanedData($this->data);
            }

            $this->isValid = true;
            return null;

        }catch (ValidationFailedException $validationFailedException){
            $this->isValid = false;
            $this->setValidationErrorMessage($validationFailedException->getMessage());
            return $validationFailedException->getMessage();
        }
    }

    /**
     * @param $data
     * @param int|null $minSize
     * @param int|null $maxSize
     * @param array|null $type
     * @throws ValidationFailedException
     */
    private function validateFile($data, ?int $minSize=null, ?int $maxSize=null, ?array $type=null): void
    {
        if(!is_null($minSize)){
            $minSize *= 1000000;
            if($data["size"] < $minSize) {
                throw new ValidationFailedException(sprintf(self::fileSizeMinError,$minSize/1000000));
            }
        }


        if(!is_null($maxSize)){
            $maxSize *= 1000000;
            if($data["size"] > $maxSize) {
                throw new ValidationFailedException(sprintf(self::fileSizeMaxError,$maxSize/1000000));
            }
        }


        if(!is_null($type)){
            $dir = "uploads/";
            $target_file = $dir . basename($data["name"]);
            $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            $type = array_values($type);
            if(!in_array(strtolower($fileType), $type, true)){
                throw new ValidationFailedException(sprintf(self::fileErrorType,implode($type)));
            }
        }
    }

}