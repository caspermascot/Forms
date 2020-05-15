<?php


namespace Casper\Fields;


use Casper\Exceptions\FieldCreateFailedException;
use Casper\FormUtils;

class Choices extends Fields
{
    /**
     * @var array
     */
    protected array $choices;

    /**
     * @param array $choices
     * @return $this
     */
    public function choices(array $choices): self
    {
        $this->choices = $choices;
        return $this;
    }

    /**
     * @param string $caller
     * @throws FieldCreateFailedException
     */
    protected function validateFieldCreate(string $caller): void
    {
        parent::validateFieldCreate($caller);
        $name = $this->getProperty('name');

        if(empty($this->choices)){
            $message = 'Missing required parameter choices, On field %s of form %s ';

            throw new FieldCreateFailedException(sprintf($message, $name, $caller));
        }
    }
    /**
     * @return array
     */
    public function asJson(): array
    {
        $res = parent::asJson();
        $res['choices'] = $this->getProperty('choices');
        return $res;
    }

    /**
     * @return array
     */
    protected function getChoiceDefault(): array
    {
        $data = [];
        if(!empty($this->data)){
            if(is_array($this->data)){
                if(FormUtils::isMultiDimensional($data)){
                    $data = array_values($this->data);
                }else{
                    $data = $this->data;
                }
            }else{
                $data = array_values(explode(',',$this->data));
            }
        }
        return $data;
    }

    /**
     * @param Choices $choices
     * @param array $data
     * @param int $count
     * @param string $label
     * @param string $choice
     * @param null $key
     * @param string $type
     * @param bool $multiple
     * @return string
     */
    protected function getChoiceHtmlData(Choices $choices, array $data, int $count, string $label, string $choice, $key=null, string $type='radio', bool $multiple=false): string
    {
        $temp = "<input type='{$type}' value='{$choice}' ";

        $temp .= !in_array($choice, $data) ? '' : 'checked="true" ';
        $temp .= empty($choices->required) ? '' : 'required="true" ';
        $temp .= empty($choices->hidden) ? '' : 'hidden="true" ';
        $temp .= empty($choices->disabled) ? '' : 'disabled="true" ';

        if(!empty($choices->autoFocus)){
            if($count == 0){
                $temp .= 'autoFocus="true" ';
            }
        }
        if(!empty($choices->name)){
            $temp .= ($multiple === true) ? " name='{$choices->name}[]' ":" name='{$choices->name}' ";
            $temp .= " id='id_{$choices->name}' ";
        }
        $temp.=" />";
        $temp.= "<label for='{$choices->getProperty('name')}'>{$label}</label> ";
        return $temp;
    }
}