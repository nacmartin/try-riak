<?php


ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__.'/vendor/autoload.php';
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;

$app['port'] = 8091;
$app['host'] = '127.0.0.1';


$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path'       => __DIR__.'/views',
));

$app->get('/', function() use($app) {
    return $app['twig']->render('try.twig', array());
});

$app->error(function (\Exception $e, $code) use ($app){
    if($app['debug'])
    {
        return;
    }
    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message, $code);
});


$app->run();
