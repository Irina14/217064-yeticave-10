<?php
require_once('functions.php');
require_once('init.php');

$categories = get_categories($link);
$rates = [];

$id = $_SESSION['user']['id'];
$sql = 'SELECT r.date_add, r.cost, r.lot_id, l.image, l.name, l.date_end, c.name AS category, u.contact FROM rates r '
     . 'JOIN lots l ON r.lot_id = l.id '
     . 'JOIN categories c ON l.category_id = c.id '
     . 'JOIN users u ON l.user_id = u.id '
     . 'WHERE r.user_id = ? ORDER BY r.date_add DESC';

$stmt = db_get_prepare_stmt($link, $sql, [$id]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    $rates = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
else {
    $error = mysquli_error($link);
    print('Ошибка: ' . $error);
}

$page_content = include_template('my-bets.php', [
    'categories' => $categories,
    'rates' => $rates,
    'link' => $link
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Мои ставки'
]);

print($layout_content);
