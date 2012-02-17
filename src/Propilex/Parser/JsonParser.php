<?php

namespace Propilex\Parser;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class JsonParser extends \PropelJSONParser
{
    /**
     * {@inheritdoc}
     */
    public function fromArray($array)
    {
        $values = array_values($array);

        if (isset($values[0]) && is_array($values[0])) {
            return parent::fromArray($values);
        } else {
            return parent::fromArray($array);
        }
    }
}
