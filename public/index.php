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

$app->get('/examples/bca', function(){
    $response =  json_decode(file_get_contents('http://kurs.dropsugar.com/rates/bca.json'));
    echo "Terakhir diupdate pada: " . date('j-m-Y H:i:s');
    foreach ($response->kurs as $currency => $value) {
        echo 'Nilai jual ' . $currency . ' adalah: ' . $value->jual . '<br>';
        echo 'Nilai beli ' . $currency . ' adalah: ' . $value->beli . '<br><br>';
    }

});

// Run app
$app->run();