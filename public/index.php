<?php
error_reporting(E_ERROR);

require '../vendor/autoload.php';
require '../vendor/phpQuery.php';
require '../models/kurs.php';

$memcache = new Memcache;
$cacheAvailable = $memcache->connect('tunnel.pagodabox.com', 11211);

// Prepare app
$app = new Slim(array(
    'templates.path' => '../templates',
    'debug' => true,
));

// Define routes
$app->get('/rates/bca(:format)', function ($format = '.json') use ($app, $memcache, $cacheAvailable) {
    $kurs = null;
    if($cacheAvailable){
        $kurs = $memcache->get('bca');
        var_dump($kurs);
    }
    if(!$kurs){
        $kurs = new Kurs;
        $kurs = $kurs->bca();
        if($kurs){
            $kurs = json_encode($kurs);
            switch ($format) {
                case '.jsonp':
                    $kurs = $app->request()->get('callback') . '(' . $kurs . ')';
                    break;
            }
            if($cacheAvailable) $memcache->set('bca', $kurs, false, 3600);
        }
    }
    
    echo $kurs;
});

// Run app
$app->run();
