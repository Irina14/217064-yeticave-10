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
    $sql = 'SELECT id, name FROM categories';
    $categories = db_select_data($link, $sql);
    $categories_ids = array_column($categories, 'id');

    $page_content = include_template('add.php', ['categories' => $categories]);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $lot = $_POST;
        $filename = uniqid() . '.jpg';
        $lot['path'] = 'uploads/' . $filename;

        move_uploaded_file($_FILES['lot-img']['tmp_name'], 'uploads/' . $filename);

        $sql = 'INSERT INTO lots (date_add, name, category_id, description, cost_start, step_rate, date_end, image, user_id) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, 1)';
        $stmt = db_get_prepare_stmt($link, $sql, $lot);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $lot_id = mysqli_insert_id($link);

            header('Location: lot.php?id=' . $lot_id);
        }
        else {
            $error = mysquli_error($link);
            print('Ошибка: ' . $error);
        }
    }
};

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Добавление лота',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
