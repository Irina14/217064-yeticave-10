<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');

$categories = get_categories($link);

$sql = 'SELECT l.name, cost_start, image, c.name AS category, date_end, l.id FROM lots l '
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
    'title' => 'Главная'
]);

print($layout_content);
