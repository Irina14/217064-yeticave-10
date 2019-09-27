<?php
require_once('functions.php');
require_once('init.php');

$categories = get_categories($link);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $rate_last = get_rate_last($link, $id);
    $cost_max = $rate_last['cost'];
    $user_rate_last = intval($rate_last['user_id']);

    $sql = 'SELECT l.name, image, c.name AS category, description, cost_start, step_rate, date_end, user_id FROM lots l '
         . 'JOIN categories c ON l.category_id = c.id '
         . 'WHERE l.id = ?';

    $stmt = db_get_prepare_stmt($link, $sql, [$id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $lot = mysqli_fetch_assoc($result);
    }
    else {
        $error = mysqli_error($link);
        print('Ошибка: ' . $error);
    }

    if (isset($lot)) {
        $cost_current = $cost_max ?? $lot['cost_start'];
        $history = get_history($link, $id);

        $page_content = include_template('lot.php', [
            'categories' => $categories,
            'lot' => $lot,
            'cost_current' => $cost_current,
            'history' => $history,
            'user_rate_last' => $user_rate_last

        ]);
    }
    else {
        show_page_404($categories);
        die();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $form = $_POST;
        $errors = [];

        if (!isset($_SESSION['user'])) {
            http_response_code(403);
            die();
        }

        $cost_min = $cost_current + $lot['step_rate'];
        $errors['cost'] = validate_rate($cost_min);
        $errors = array_filter($errors);

        if (empty($form['cost'])) {
            $errors['cost'] = 'Заполните это поле';
        }

        if (count($errors)) {
            $page_content = include_template('lot.php', [
                'categories' => $categories,
                'lot' => $lot,
                'cost_current' => $cost_current,
                'errors' => $errors,
                'history' => $history,
                'user_rate_last' => $user_rate_last
            ]);
        }
        else {
            $cost = intval($form['cost']);
            $user_id = intval($_SESSION['user']['id']);
            $sql = 'INSERT INTO rates (date_add, cost, user_id, lot_id) VALUES (NOW(), ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$cost, $user_id, $id]);
            $result = mysqli_stmt_execute($stmt);
        }

        if (!count($errors) && $result) {
            $cost_current = $cost;
            $history = get_history($link, $id);
            $user_rate_last = $user_id;

            $page_content = include_template('lot.php', [
                'categories' => $categories,
                'lot' => $lot,
                'cost_current' => $cost_current,
                'history' => $history,
                'user_rate_last' => $user_rate_last
            ]);
        } elseif (!$result) {
            $error = mysqli_error($link);
            print('Ошибка: ' . $error);
        }
    }

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
