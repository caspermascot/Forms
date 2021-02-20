<?php


namespace Casper;


class FormUtils
{
    /**
     * @param $text
     * @return string
     */
    public static function slugify($text): string
    {
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $text));
    }

    /**
     * @param array $array
     * @return bool
     */
    public static function isMultiDimensional(array $array): bool
    {
        return (!empty($array)) and array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * @param object $field
     * @return string
     */
    public static function getFieldType(object $field): string
    {
        $fieldType = explode("\\", get_class($field));
        return lcfirst($fieldType[count($fieldType)-1]);
    }
}