<?php
require_once('functions.php');
require_once('init.php');

if (isset($_SESSION['user'])) {
    http_response_code(403);
    die();
}

$categories = get_categories($link);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;

    $required = ['email', 'password', 'name', 'message'];
    $errors = [];

    if (!(filter_var($form['email'], FILTER_VALIDATE_EMAIL))) {
        $errors['email'] = 'Некорректный email';
    }

    foreach ($required as $key) {
        if (empty($form[$key])) {
            $errors[$key] = 'Заполните это поле';
        }
    }

    if (empty($errors)) {
        $email = mysqli_real_escape_string($link, $form['email']);
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $result = mysqli_query($link, $sql);

        if (mysqli_num_rows($result) > 0) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        } else {
            $password = password_hash($form['password'], PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (date_add, email, name, password, contact) VALUES (NOW(), ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$form['email'], $form['name'], $password, $form['message']]);
            $result = mysqli_stmt_execute($stmt);
        }

        if ($result && empty($errors)) {
            header('Location: login.php');
            die();
        }
    }

    $page_content = include_template('sign-up.php', [
        'form' => $form,
        'errors' => $errors,
        'categories' => $categories
    ]);
} else {
    $page_content = include_template('sign-up.php', ['categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Регистрация'
]);

print($layout_content);
