<?php
error_reporting(E_ALL);
session_start();

require_once '../vendor/autoload.php';
require_once '../app/assets/helpers.php';
require_once '../app/routes/init.php';

$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates/');
$twig = new Twig_Environment($loader, array(
	'cache' => __DIR__ . '/storage/cache/',
));

echo $twig->render('index.html', array('name' => 'Fabien'));