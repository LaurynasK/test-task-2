<?php
/**
 * Created by PhpStorm.
 * User: Laurynas
 * Date: 2018-05-22
 * Time: 23:50
 */

namespace App\Service;


use JsonSchema\Validator;

class SchemaJsonHelper
{
    /**
     * @var Validator
     */
    public $validator;

    /**
     * SchemaJsonHelper constructor.
     */
    public function __construct()
    {
        $this->validator = new Validator();

    }

    /**
     * @param array $data
     * @param $schemaJson
     *
     * @return array
     */
    public function checkValidation(array $data, $schemaJson)
    {
        if(is_string($schemaJson)) $schemaJson = json_decode($schemaJson);
        $final = array();
        foreach ($data as $item) {
            $item = (object) $item;
            $this->validator->validate( $item, $schemaJson);
            if ($this->validator->isValid()) {
                $final[] = (array) $item;
            }
        }

        return $final;
    }
}