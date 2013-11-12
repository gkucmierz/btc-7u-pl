<?php

error_reporting(0);
if (!isset($_POST['get'])) {
    exit;
}

$_POST = array();

ob_start();
chdir('../');
include('post.php');
$json = ob_get_clean();

$res = json_decode($json, JSON_FORCE_OBJECT);

echo implode($res['concat'], "\n");