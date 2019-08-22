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
