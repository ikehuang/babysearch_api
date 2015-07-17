<?php

/**
 * Very simple MVC structure
 */
$loader = new \Phalcon\Loader();

$loader->registerDirs(array('../apps/controllers/', '../apps/models/'));

$loader->register();

$di = new \Phalcon\DI();
//Registering a dispatcher

$di->set('dispatcher', function () use ($di) {
	//Attach a event listener to the dispatcher
	$eventsManager = $di->getShared('eventsManager');
	$eventsManager->attach('dispatch', new \Acl());

	$dispatcher = new \Phalcon\Mvc\Dispatcher();
	//Bind the EventsManager to the Dispatcher
	$dispatcher->setEventsManager($eventsManager);

	return $dispatcher;
});

// set session
$di->setShared('session', function() {
	$session = new Phalcon\Session\Adapter\Files();
	$session->start();
	return $session;
});
	
//Registering a router
$di->set('router', 'Phalcon\Mvc\Router');

//Registering a dispatcher
$di->set('dispatcher', 'Phalcon\Mvc\Dispatcher');

//Registering a Http\Response
$di->set('response', 'Phalcon\Http\Response');

//Registering a Http\Request
$di->set('request', 'Phalcon\Http\Request');

//Registering the view component
$di->set('view', function(){
	$view = new \Phalcon\Mvc\View();
	$view->setViewsDir('../apps/views/');
	return $view;
});

	$config = new \Phalcon\Config\Adapter\Ini("../apps/config/config.ini");
	
	$di->set('config',$config);

//Registering the Models-Metadata
$di->set('modelsMetadata', 'Phalcon\Mvc\Model\Metadata\Memory');

//Registering the Models Manager
$di->set('modelsManager', 'Phalcon\Mvc\Model\Manager');

//Registering the Assets Manager
$di->set('assets', 'Phalcon\Assets\Manager');

//Registering the URL Service
$di->set('url', '\Phalcon\Mvc\Url');

//Registering the Escaper Service
$di->set('escaper', '\Phalcon\Escaper');

$config = $di->get('config');
$db_config = $config->dev_database ;

$db = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
		"host" => $db_config->host,
		"username" => $db_config->username,
		"password" => $db_config->password,
		"dbname" => $db_config->dbname,
		'charset' => 'latin1'
));

$di->set('db',$db);

//Registering a Http\Response
$di->set('response', 'Phalcon\Http\Response');

//Registering a Http\Request
$di->set('request', 'Phalcon\Http\Request');

try {
	$application = new \Phalcon\Mvc\Application();
	$application->setDI($di);
	echo $application->handle()->getContent();
}
catch(Phalcon\Exception $e){
	echo $e->getMessage();
}
