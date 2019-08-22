<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');

$is_auth = rand(0, 1);
$user_name = 'Irina';

if (!$link) {
    $error = mysqli_connect_error();
    print('Ошибка подключения: ' . $error);
}
else {
    $sql = 'SELECT name, code FROM categories';
    $categories = db_select_data($link, $sql);

    $sql = 'SELECT l.name, cost_start, image, c.name AS category, date_end FROM lots l '
         . 'JOIN categories c ON l.category_id = c.id '
         . 'WHERE date_end > NOW() ORDER BY date_add DESC LIMIT 9';
    $lots = db_select_data($link, $sql);

    $page_content = include_template('main.php', [
        'categories' => $categories,
        'lots' => $lots
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Главная',
        'user_name' => $user_name,
        'is_auth' => $is_auth
    ]);

    print($layout_content);
}
