<?php
require('vendor/autoload.php');
require('ShortUrl.php');
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
$app = new \Slim\App();


$origin_url = "www.google.com";


$app->post('/', function($request, $response, $args) {
try {
	$_input = $request->getParsedBody();
	$origin_url = str_replace(['http://', 'https://'], '', $_input['origin_url']);

	$dbh = new PDO(
        'mysql:host=127.0.0.1;dbname=test;charset=utf8mb4', 
        'root', 
        ''
    );
    $sth = $dbh->prepare("SELECT short_url FROM `short_url` where origin_url = :origin_url ");
    $sth->execute(['origin_url'=> $origin_url]);
    $result = $sth->fetch();

    if (!$result) {
    	$url = new ShortUrl();
    	$duanurl = $url -> short_url($origin_url);
    	echo 'localhost/slim-app/Hello.php?url='.$duanurl;
    	$sql = "Insert into `short_url` value (null, :short_url, :origin_url) ";
    	$row = [
	        'id' => null,
	        'short_url' => $duanurl,
	        'origin_url' => $origin_url
	    ];

   	 	$sql = "INSERT INTO short_url SET id=:id, short_url=:short_url, origin_url=:origin_url;";


    	$success = $dbh->prepare($sql)->execute($row);

    } else {
    	echo 'localhost/slim-app/Hello.php?url='.$result['short_url'];
    }

} catch (PDOException $e) {
    die("Something wrong: {$e->getMessage()}");
}
});


$app->get('/', function($request, $response, $args) {
    $_input = $request->getQueryParams();
	$short_url = $_input['url'];
	$dbh = new PDO(
        'mysql:host=127.0.0.1;dbname=test;charset=utf8mb4', 
        'root', 
        ''
    );
    $sth = $dbh->prepare("SELECT origin_url FROM `short_url` where short_url =:short_url ");
    $sth->execute(['short_url'=>$short_url]);
    $result = $sth->fetch();
    return $response->withStatus(303)->withHeader('Location', "https://".$result['origin_url']);
});

$app->run();


?>