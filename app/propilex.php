<?php

$app = require_once __DIR__ . '/config/config.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/*
$users = Propilex\Model\UserQuery::selectUsersList();
$usersRole = array();
foreach ($users as $user ) {
    if ($user['id'] == 0) {
    	$usersRole[] = array('ROLE_ADMIN', '');
    }
    else {
        $usersRole[] = array('ROLE_USER', '');
    }
}

$app['security.firewalls'] = array(
    'admin' => array(
        'pattern' => '^/private', 
        'http' => true, 
        'users' => $usersRole
    )
);
*/

/**
 * @see http://silex.sensiolabs.org/doc/cookbook/json_request_body.html
 */
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);

        // filter values
        foreach ($data as $k => $v) {
            if (false === array_search($k, array('Id', 'Title', 'Body', 'Firstname', 'Lastname', 'Location_Id', 'Description', 'Email'))) {
                unset($data[$k]);
            }
        }

        $request->request->replace(is_array($data) ? $data : array());
    }
});

// Put user token on twig template
/*
$app->before(function (Request $request) use ($app) {
    $token = $app['security']->getToken();
    if (null !== $token) {
        $user = $token->getUser();
        // @todo Put variable on twig template
    }
});
*/

/**
 * Entry point
 */
$app->get('/', function() use ($app) {
    $users = Propilex\Model\UserQuery::selectUsers();
    return $app['twig']->render('index.html.twig', array('users' => json_encode($users) ) );
});


/**
 * Security page
 */
$app->get('/private', function() use ($app) {
    $users = Propilex\Model\UserQuery::selectUsersList();
    return $app['twig']->render('private.html.twig', array('users' => $users) );
});
    
/**
 * Register a REST controller to manage documents
 */
$app->mount('/users', new Propilex\Provider\RestController(
	'user', '\Propilex\Model\User', 'getUpdatedAt'
));

/**
 * Get users activities
 */
$app->get('/activities', function() use ($app) {
    $activities = array(
    	array(
    		'date' => '2012-08-16 07:12:02',
    		'message' => 'Update User with id 3', 
            'type' => 'user_update'
        ), 
        array(
            'date' => '2012-08-16 07:14:02', 
            'message' => 'Update User with id 2',
    		'type' => 'user_update'
    	),
    );
    return $app['twig']->render('activities.html.twig', array('activities' => $activities) );
});

return $app;
