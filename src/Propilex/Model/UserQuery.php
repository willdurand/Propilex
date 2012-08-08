<?php

namespace Propilex\Model;

use Propilex\Model\om\BaseUserQuery;


/**
 * Skeleton subclass for performing query and update operations on the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.Propilex.Model
 */
class UserQuery extends BaseUserQuery
{
    /**
     * Prepare all users's datas
     * @return array
     */
    public static function selectUsers()
    {
        $users = self::create()
    			->join('Location')
    			->select(array('Id', 'Firstname', 'Lastname', 'Affiliation', 'Description', 'Answered', 'Location.Title'))
    			->find()
        		->toArray();
        
        foreach ($users as $i => $user) {
            $meals = UsermealQuery::create()
            		->join('Meal')
            		->select(array('Meal.Type', 'Meal.Date', 'Number' ) )
            		->where(UsermealPeer::USER_ID . ' = ?', $user['Id'])
            		->find()
            		->toArray();
            
            foreach ($meals as $j => $meal) {
	            $users[$i]['Meal'][$j]['Meal.Type'] = $meal['Meal.Type'];
	            $users[$i]['Meal'][$j]['Meal.Date'] = $meal['Meal.Date'];
	            $users[$i]['Meal'][$j]['Meal.Number'] = $meal['Number'];
            }
        }
        
        return $users;
    }
}
