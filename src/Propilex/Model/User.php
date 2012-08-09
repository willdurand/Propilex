<?php

namespace Propilex\Model;

use Propilex\Model\om\BaseUser;
use \PropelException;


/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.Propilex.Model
 */
class User extends BaseUser
{
    /**
     * Set the value of [answered] column.
     *
     * @param int $v new value
     * @return User The current object (for fluent API support)
     * @throws PropelException - if the value is not accepted by this enum.
     */
    public function setAnswered($v)
    {
        if (intval($v) == $v) {
            $v = self::getAnsweredValue(intval($v) );
        }
        
        return parent::setAnswered($v);
    }
    
    /**
     * Get index of string ENUM value
     * @param string $v
     * @throws PropelException - if the value is not accepted by this enum
     * @return int
     */
    public static function getAnsweredIndex($v)
    {
        $valueSet = UserPeer::getValueSet(UserPeer::ANSWERED);
        $key = array_search($v, $valueSet);
        if ($key === false) {
            throw new PropelException('Unknown stored enum key: ' . $v);
        }
        return $key;
    }
    
    /**
     * Get value of a int key ENUM
     * @param int $v
     * @throws PropelException - if the key is not accepted by this enum
     * @return string
     */
    public static function getAnsweredValue($v)
    {
        $valueSet = UserPeer::getValueSet(UserPeer::ANSWERED);
        if (!isset($valueSet[$v])) {
            throw new PropelException('Unknown stored enum key: ' . $v);
        }
        return $valueSet[$v];
    }
}
