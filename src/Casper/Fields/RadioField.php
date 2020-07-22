<?php


namespace Casper\Fields;


use Casper\FormUtils;

class RadioField extends Choices
{
    /**
     * @param string $name
     * @return string
     */
    public function asHtml(string $name=''): string
    {
        $res = parent::asHtml($name);
        $field = '';
        $data = $this->getChoiceDefault();
        $count = 0;
        if(FormUtils::isMultiDimensional($this->choices)){
            foreach ($this->choices as $key => $choice){
                $label = ucfirst($key);
                $field.= $this->getChoiceHtmlData($this, $data, $count, $label, $choice, $key);
                $count+=1;
            }
        }else{
            foreach ($this->choices as $key => $choice){
                $label = ucfirst($choice);
                $field.= $this->getChoiceHtmlData($this, $data, $count, $label, $choice, null);
                $count+=1;
            }
        }
        $res = str_replace('htmlField', $field, $res);
        return $res;
    }
}