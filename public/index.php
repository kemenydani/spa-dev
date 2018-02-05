<?php
error_reporting(E_ALL);
session_start();

require_once '../vendor/autoload.php';
require_once '../app/assets/helpers.php';
require_once '../app/routes/init.php';

/*
use \core\DB2 as DB2;

$db = DB2::instance();

$Article = new \models\Article();

$A = $Article::find('id', 11);

var_dump($A->getPublicProperties());

*/
// find: get one item where the column value = param find(table, column, value)
// findAll: get all items where the column value = param find(table, column, value)
// all: get all items items

// insert
// update

// delete
// deleteIn


