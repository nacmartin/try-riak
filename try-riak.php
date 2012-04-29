<?php


ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/markdown.php';
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();
$app['debug'] = true;

$app['port'] = 8091;
$app['host'] = 'http://127.0.0.1';
$app['tutorial_dir'] = __DIR__.'/tutorial/';


$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path'       => __DIR__.'/views',
));
$app->register(new Silex\Provider\SessionServiceProvider());

$app->get('/', function() use($app) {
    $content = file_get_contents($app['tutorial_dir']."step1.md");
    $tutorial = Markdown($content);
    return $app['twig']->render('try.twig', array('tutorial' => $tutorial));
});

$app->get('/next', function(Request $request) use($app) {
    $app['session']->start();
    $step = $app['session']->get('step') ? : 0;
    $maxStep = count(glob($app['tutorial_dir']. "step*.md"));
    if($step < $maxStep) {
        $step ++;
    } else {
        $step = $maxStep;
    }
    $app['session']->set('step', $step);
    $content = file_get_contents($app['tutorial_dir']."step$step.md");
    $tutorial = Markdown($content);
    return $tutorial;
});

$app->get('/prev', function(Request $request) use($app) {
    $app['session']->start();
    $step = $app['session']->get('step') ? : 0;
    if ($step > 0) {
        $step --;
    } else {
        $step = 0;
    }
    $app['session']->set('step', $step);
    $content = file_get_contents($app['tutorial_dir']."step$step.md");
    $tutorial = Markdown($content);
    return $tutorial;
});

$app->get('/command', function(Request $request) use($app) {
    $urlreq = $request->get('url');
    if(strpos($urlreq, '/') !== 0) {
        $urlreq = '/'.$urlreq;
    }
    if(strpos($urlreq, '/mapred') === 0) {
        return json_encode(array('error' => 'mapred not implemented in this interactive tutorial due to security risks, it is too powerful!'));
    }

    $url = $app['host'].$urlreq;
    $headers = array($request->get('header'));
    $data = $request->get('data');
    $method = $request->get('method');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_PORT, $app['port']);
    switch ($method) {
    case 'POST':
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        break;
    case 'PUT':
        $url .= '?returnbody=true';
        $file = tmpfile();
        fwrite($file, $data);
        fseek($file, 0);
        $headers[] = 'X-Riak-Vclock: a85hYGBgzGDKBVIszMk55zKYEhnzWBlKIniO8mUBAA==';
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        break;
    case 'DELETE':
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        break;
    case 'GET':
    default:
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        break;
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    $rethead = '';
    curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($ch, $str) use (&$rethead) {
        $rethead .= $str;
        return strlen($str);
    });
    $retbody = '';
    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $str) use (&$retbody) {
        $retbody .= $str;
        return strlen($str);
    });
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_exec($ch);

    return json_encode(array('response' => $retbody, 'header' => $rethead));
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
