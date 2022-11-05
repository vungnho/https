<?php

function xPHP() {
	if (isset($_COOKIE['host'])) {
		$host = $_COOKIE['host'];
		$uri = $_SERVER['REQUEST_URI'];
		if($uri == '/')
			$uri = '';
		setcookie('lastPage', $host.$uri, 0, '/', null, true, true);
		//echo ($host.$uri);
		//$content = file_get_contents($host.$uri);
		$ch = curl_init($host.$uri);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$content = curl_exec($ch);
		$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		curl_close($ch);
		header('Content-Type: '.$content_type);
		if($content != '') {
			echo str_replace($host,'',$content);
			//echo $content;
		}
		exit();
	}
	echo " \n";
}
xPHP();

try {
require('../vendor/autoload.php');
} catch (Exception $e) { exit(); }

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});

$app->error(function (\Exception $e, $code) use ($app) {

    // commented for testing purposes
    /*if ($app['debug']) {
        return;
    }*/

    if ($code == 404) {
	xPHP();
	
        $loader = $app['dataloader'];
        $data = array(
            'global' => $loader->load('global'),
            'common' => $loader->load('common', $app['locale']),
            'header' => $loader->load('header', $app['locale']),
            'footer' => $loader->load('footer', $app['locale'])
        );

        //return new Response( $app['twig']->render('404.html.twig', array( 'data' => $data )), 404);
        return new Response('We are sorry, but something went terribly wrong.', $code);
    }

    return new Response('We are sorry, but something went terribly wrong.', $code);

});

$app->run();

?>
