<?php
require_once('functions.php');
require_once('init.php');

$categories = get_categories($link);
$category = $_GET['category'] ?? '';
$cur_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$lots = [];
$items_count = [];
$pages = 1;
$pages_count = 1;
$page_items = 9;

if ($category) {
    $sql = 'SELECT COUNT(*) as count FROM lots l '
         . 'JOIN categories c ON l.category_id = c.id '
         . 'WHERE c.name = ? AND date_end > NOW()';

    $stmt = db_get_prepare_stmt($link, $sql, [$category]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $items_count = mysqli_fetch_assoc($result)['count'];
    } else {
        $error = mysqli_error($link);
        print('Ошибка: ' . $error);
    }

    $pages_count = (int) ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $sql = 'SELECT l.name, image, c.name AS category, cost_start, step_rate, date_end, l.id FROM lots l '
         . 'JOIN categories c ON l.category_id = c.id '
         . 'WHERE c.name = ? AND date_end > NOW() '
         . 'ORDER BY date_add DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;

    $stmt = db_get_prepare_stmt($link, $sql, [$category]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        print('Ошибка: ' . $error);
    }
}

$page_content = include_template('all-lots.php', [
    'categories' => $categories,
    'lots' => $lots,
    'link' => $link,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page,
    'item' => $category,
    'target'=> 'category',
    'path' => 'all-lots.php'
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Все лоты'
]);

print($layout_content);
