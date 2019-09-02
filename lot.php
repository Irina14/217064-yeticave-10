<?php
require_once('helpers.php');
require_once('functions.php');
require_once('init.php');

$categories = get_categories($link);

if (isset($_GET['id'])) {
    $id = [intval($_GET['id'])];

    $sql = 'SELECT l.name, image, c.name AS category, description, cost_start, step_rate, date_end FROM lots l '
            . 'JOIN categories c ON l.category_id = c.id '
            . 'WHERE l.id = ?';

    $stmt = db_get_prepare_stmt($link, $sql, $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $lot = mysqli_fetch_assoc($result);
    }
    else {
        $error = mysquli_error($link);
        print('Ошибка: ' . $error);
    }

    if (isset($lot)) {
        $page_content = include_template('lot.php', [
            'categories' => $categories,
            'lot' => $lot
        ]);

        $title = $lot['name'];

        $layout_content = include_template('layout.php', [
            'content' => $page_content,
            'categories' => $categories,
            'title' => $title
        ]);

        print($layout_content);
    }
    else {
        show_page_404($categories);
    }
}
else {
    show_page_404($categories);
}
