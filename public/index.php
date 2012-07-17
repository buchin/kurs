<?php
error_reporting(E_ERROR);

require '../vendor/autoload.php';
require '../vendor/phpQuery.php';
require '../models/kurs.php';

$memcache = new Memcached();
$cacheAvailable = $memcache->addServer('tunnel.pagodabox.com', 11211);

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
    }
    
    if($kurs ==  false){
        $kurs = new Kurs;
        $kurs = $kurs->bca();
        if(!empty($kurs)){
            $kurs = json_encode($kurs);
            
            if($cacheAvailable) {
                $status = $memcache->set('bca', $kurs, time()+3600);
            }
        }
    }
    
    switch ($format) {
        case '.jsonp':
            $kurs = $app->request()->get('callback') . '(' . $kurs . ')';
            break;
    }
    
    echo $kurs;
});

// Run app
$app->run();
