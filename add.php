<?php
require_once('functions.php');
require_once('init.php');

if (!isset($_SESSION['user'])) {
    http_response_code(403);
    print('Ошибка доступа: Требуется войти в свою учетную запись!');
    die();
}

$categories = get_categories($link);
$cats_ids = array_column($categories, 'id');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lot = $_POST;

    $required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $errors = [];

    $rules = [
        'lot-name' => function () {
            return validate_length('lot-name', 1, 150);
        },
        'category' => function () use ($cats_ids) {
            return validate_category('category', $cats_ids);
        },
        'lot-rate' => function () {
            return validate_cost_start('lot-rate');
        },
        'lot-step' => function () {
            return validate_step_rate('lot-step');
        },
        'lot-date' => function () {
            return validate_date_end('lot-date');
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
        } else {
            $filename = get_filename($file_type);
            $lot['path'] = 'uploads/' . $filename;
            move_uploaded_file($tmp_name, 'uploads/' . $filename);
        }
    } else {
        $errors['file'] = 'Загрузите файл';
    }

    if (count($errors)) {
        $page_content = include_template('add.php', [
            'lot' => $lot,
            'errors' => $errors,
            'categories' => $categories
        ]);
    } else {
        $lot['user_id'] = intval($_SESSION['user']['id']);
        $sql = 'INSERT INTO lots (date_add, name, category_id, description, cost_start, step_rate, date_end, image, user_id) '
             . 'VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, $lot);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $lot_id = mysqli_insert_id($link);
            header('Location: lot.php?id=' . $lot_id);
            die();
        } else {
            $error = mysqli_error($link);
            print('Ошибка: ' . $error);
        }
    }
} else {
    $page_content = include_template('add.php', ['categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Добавление лота'
]);

print($layout_content);
