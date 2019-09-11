<?php
require_once('functions.php');
require_once('init.php');

$categories = get_categories($link);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;

    $required = ['email', 'password'];
    $errors = [];

    if (!(filter_var($form['email'], FILTER_VALIDATE_EMAIL))) {
        $errors['email'] = 'Некорректный email';
    }

    foreach ($required as $key) {
        if (empty($form[$key])) {
            $errors[$key] = 'Заполните это поле';
        }
    }

    if (count($errors)) {
        $page_content = include_template('login.php', [
            'form' => $form,
            'errors' => $errors,
            'categories' => $categories
        ]);
    } else {
        $email = mysqli_real_escape_string($link, $form['email']);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($link, $sql);
        $user = $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : null;

        if (!$user) {
            $errors['email'] = 'Такой пользователь не найден';
        } elseif (!password_verify($form['password'], $user['password'])) {
            $errors['password'] = 'Вы ввели неверный пароль';
        } else {
            $_SESSION['user'] = $user;
            header('Location: index.php');
            die();
        }
    }

    $page_content = include_template('login.php', [
        'form' => $form,
        'errors' => $errors,
        'categories' => $categories
    ]);
} else {
    $page_content = include_template('login.php', ['categories' => $categories]);

    if (isset($_SESSION['user'])) {
        header('Location: index.php');
        die();
    }
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Вход'
]);

print($layout_content);
