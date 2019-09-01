<?php
function format_sum($number) {
    $number_rounded = ceil($number);

    if ($number_rounded >= 1000) {
        $number_format = number_format($number_rounded, 0, '.', ' ');
    }
    else {
        $number_format = $number_rounded;
    }

    $sum = $number_format . ' ' . '&#8381;';
    return $sum;
};

date_default_timezone_set("Europe/Moscow");

function get_date_range($date) {
    $result = [];
    $date_end = strtotime($date);
    $date_diff = $date_end - time();
    $hours = (string) floor($date_diff / 3600);
    $minutes = (string) floor(($date_diff % 3600) / 60);

    if (mb_strlen($hours) < 2) {
        $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
    }

    if (mb_strlen($minutes) < 2) {
        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
    }

    $result[] = $hours;
    $result[] = $minutes;
    return $result;
};

function db_select_data($link, $sql) {
    $data = [];
    $result = mysqli_query($link, $sql);

    if ($result) {
        $data= mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $error = mysquli_error($link);
        print('Ошибка: ' . $error);
    }

    return $data;
};

function show_page_404($categories, $user_name, $is_auth) {
    $page_content = include_template('404.php', ['categories' => $categories]);

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'title' => '404',
        'user_name' => $user_name,
        'is_auth' => $is_auth
    ]);

    print($layout_content);
};

function get_post_val($name) {
    return $_POST[$name] ?? '';
};

function validate_category($name, $allowed_list) {
    $id = $_POST[$name];

    if (!in_array($id, $allowed_list)) {
        return 'Указана несуществующая категория';
    }

    return null;
};

function validate_cost_start() {
    $cost_start = $_POST['lot-rate'];

    if (!(is_numeric($cost_start) && $cost_start > 0)) {
        return 'Начальная цена должна быть числом больше нуля';
    }

    return null;
};

function validate_step_rate() {
    $step_rate = $_POST['lot-step'];
    $options = array(
        'options' => array(
            'min_range' => 1
        )
    );

    if (!(filter_var($step_rate, FILTER_VALIDATE_INT, $options))) {
        return 'Шаг ставки должен быть целым числом больше нуля';
    }

    return null;
};

function validate_date_end() {
    $date_end = $_POST['lot-date'];
    $date_end_unix = strtotime($date_end);
    $date_now_unix = strtotime('now');
    $diff = $date_end_unix - $date_now_unix;

    if (!(is_date_valid($date_end) && $diff >= 86400)) {
        return 'Дата должна быть в формате ГГГГ-ММ-ДД и больше текущей даты минимум на одни сутки';
    }

    return null;
};

function get_filename($file_type) {
    $filename = '';

    if ($file_type === 'image/png') {
        $filename = uniqid() . '.png';
    }

    if ($file_type === 'image/jpeg') {
        $filename = uniqid() . '.jpg';
    }

    return $filename;
};

