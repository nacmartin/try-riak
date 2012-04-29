<?php


ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__.'/vendor/autoload.php';
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();
$app['debug'] = true;

$app['port'] = 8091;
$app['host'] = 'http://127.0.0.1';


$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path'       => __DIR__.'/views',
));

$app->get('/', function() use($app) {
    return $app['twig']->render('try.twig', array());
});

$app->get('/command', function(Request $request) use($app) {
    $urlreq = $request->get('url');
    if(strpos($urlreq, '/') !== 0) {
        $urlreq .= '/';
    }
    $url = $app['host'].$urlreq;
    $headers = array($request->get('header'));
    $data = $request->get('data');
    $method = $request->get('method');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_PORT, $app['port']);
    switch ($method) {
    case 'POST':
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        break;
    case 'PUT':
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_POSTFIELDSIZE, strlen($data));
        break;
    case 'DELETE':
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        break;
    case 'GET':
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        break;
    default:
        return "invalid method";
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $output = curl_exec($ch);
    echo $output;

    return $output;
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
