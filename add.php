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
    $cats_ids = array_column($categories, 'id');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $lot = $_POST;

        $required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
        $errors = [];

        $rules = [
            'category' => function() use ($cats_ids) {
                return validate_category('category', $cats_ids);
            },
            'lot-rate' => function() {
                return validate_cost_start();
            },
            'lot-step' => function() {
                return validate_step_rate();
            },
            'lot-date' => function() {
                return validate_date_end();
            }
        ];

        foreach ($_POST as $key => $value) {
            if (isset($rules[$key])) {
                $rule = $rules[$key];
                $errors[$key] = $rule();
            }
        }

        $errors = array_filter($errors);

        foreach ($required as $key) {
            if (empty($_POST[$key])) {
                $errors[$key] = 'Заполните это поле';
            }
        }

        if (isset($_FILES['lot-img']['name']) && ($_FILES['lot-img']['name'] !== '')) {
            $tmp_name = $_FILES['lot-img']['tmp_name'];
            $file_type = mime_content_type($tmp_name);

            if ($file_type !== 'image/png' && $file_type !== 'image/jpeg') {
                $errors['file'] = 'Допустимые форматы файлов: jpg, jpeg, png';
            }
            else {
                $filename = get_filename($file_type);
                $lot['path'] = 'uploads/' . $filename;
                move_uploaded_file($tmp_name, 'uploads/' . $filename);
            }
        }
        else {
            $errors['file'] = 'Загрузите файл';
        }

        if (count($errors)) {
            $page_content = include_template('add.php', [
                'lot' => $lot,
                'errors' => $errors,
                'categories' => $categories
            ]);
        }
        else {
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
    }
    else {
        $page_content = include_template('add.php', ['categories' => $categories]);
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
