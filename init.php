<?php
date_default_timezone_set('Asia/Omsk');
session_start();
$db = require_once 'config/db.php';

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, 'utf8');

if (!$link) {
    $error = mysqli_connect_error();
    print('Ошибка подключения: ' . $error);
    die();
}
