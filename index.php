<?php
require_once('helpers.php');

$is_auth = rand(0, 1);

$user_name = 'Irina';

$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

$lots = [
    [
        'title' => '2014 Rossignol District Snowboard',
        'category' => 'Доски и лыжи',
        'cost' => 10999,
        'image' => 'img/lot-1.jpg',
        'date_end' => '2019-08-17'
    ],
    [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 'Доски и лыжи',
        'cost' => 159999,
        'image' => 'img/lot-2.jpg',
        'date_end' => '2019-08-15 22:00'
    ],
    [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 'Крепления',
        'cost' => 8000,
        'image' => 'img/lot-3.jpg',
        'date_end' => '2019-08-16'
    ],
    [
        'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 'Ботинки',
        'cost' => 10999,
        'image' => 'img/lot-4.jpg',
        'date_end' => '2019-08-18'
    ],
    [
        'title' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 'Одежда',
        'cost' => 7500,
        'image' => 'img/lot-5.jpg',
        'date_end' => '2019-08-16'
    ],
    [
        'title' => 'Маска Oakley Canopy',
        'category' => 'Разное',
        'cost' => 5400,
        'image' => 'img/lot-6.jpg',
        'date_end' => '2019-08-19'
    ],
];

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

$page_content = include_template('main.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
