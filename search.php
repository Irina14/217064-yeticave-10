<?php
require_once('functions.php');
require_once('init.php');

$categories = get_categories($link);
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$cur_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$lots = [];
$items_count = [];
$pages = 1;
$pages_count = 1;
$page_items = 9;

if ($search) {
    $sql = 'SELECT COUNT(*) as count FROM lots '
         . 'WHERE MATCH(name, description) AGAINST(?) AND date_end > NOW()';

    $stmt = db_get_prepare_stmt($link, $sql, [$search]);
	mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $items_count = mysqli_fetch_assoc($result)['count'];
    }
    else {
        $error = mysquli_error($link);
        print('Ошибка: ' . $error);
    }

    $pages_count = (int) ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $sql = 'SELECT l.name, cost_start, image, c.name AS category, date_end, l.id FROM lots l '
         . 'JOIN categories c ON l.category_id = c.id '
         . 'WHERE MATCH(l.name, description) AGAINST(?) AND date_end > NOW() '
         . 'ORDER BY date_add DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;

    $stmt = db_get_prepare_stmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $error = mysquli_error($link);
        print('Ошибка: ' . $error);
    }
}

$page_content = include_template('search.php', [
    'categories' => $categories,
    'lots' => $lots,
    'search' => $search,
    'link' => $link,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page,
    'path' => 'search.php'
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Результаты поиска'
]);

print($layout_content);
